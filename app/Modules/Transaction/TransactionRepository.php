<?php

namespace App\Modules\Transaction;

use Illuminate\Support\Facades\DB;
use App\Modules\Transaction\Transaction;

class TransactionRepository
{
    private \PDO $connection;

    public function __construct()
    {
        $this->connection = DB::connection()->getPdo();
    }

    public function getAllWithUser()
    {
        $query = <<<SQL
            SELECT
                t.id as id,
                t.created_at as time,
                t.user_id as user_id,
                u.name as user_name,
                t.total_price as total_price
            FROM transactions t
            INNER JOIN users u ON t.user_id = u.id
        SQL;

        $statement = $this->connection->prepare($query);
        $statement->execute();

        $transactions = [];
        try {
            while (($result = $statement->fetch())) {
                $transactions[] = new Transaction(
                    $result["id"],
                    $result["user_id"],
                    $result["user_name"],
                    $result["total_price"],
                    $result["time"]
                );
            }
        } finally {
            $statement->closeCursor();
        }

        return $transactions;
    }

    public function getById(int $id)
    {
        $query = <<<SQL
            SELECT
                t.id as id,
                t.created_at as time,
                t.user_id as user_id,
                u.name as user_name,
                t.total_price as total_price
            FROM transactions t
            INNER JOIN users u ON t.user_id = u.id AND t.id = ?
        SQL;

        $statement = $this->connection->prepare($query);
        $statement->execute([$id]);

        try {
            if (($result = $statement->fetch())) {
                return new Transaction(
                    $result["id"],
                    $result["user_id"],
                    $result["user_name"],
                    $result["total_price"],
                    $result["time"]
                );
            }
            else return null;
        } finally {
            $statement->closeCursor();
        }
    }

    public function getDetailsById(int $id)
    {
        $query = <<<SQL
            SELECT
                td.quantity as quantity,
                f.name as food_name,
                td.price as food_price
            FROM transaction_details td
            INNER JOIN foods f ON td.food_id = f.id AND td.transaction_id = ?
        SQL;

        $statement = $this->connection->prepare($query);
        $statement->execute([$id]);

        $transactionDetails = [];
        try {
            while (($result = $statement->fetch())) {
                $transactionDetails[] = new TransactionDetails(
                    $result["quantity"],
                    $result["food_name"],
                    $result["food_price"]
                );
            }
        } finally {
            $statement->closeCursor();
        }
        return $transactionDetails;
    }

    public function insert(int $total_price)
    {
        $query = <<<SQL
            INSERT INTO transactions (user_id, total_price)
            values (?, ?)
        SQL;

        try {
            $statement = $this->connection->prepare($query);
            $status = $statement->execute([
                auth()->user()->getAuthIdentifier(),
                $total_price
            ]);
        } finally {
            $statement->closeCursor();
        }

        if ($status) return $this->connection->lastInsertId();
        return null;
    }

    public function insertDetails(int $transactionId, array $item)
    {
        $query = <<<SQL
            INSERT INTO transaction_details (quantity, transaction_id, food_id, price)
            values (?, ?, ?, ?)
        SQL;

        try {
            $statement = $this->connection->prepare($query);
            $status = $statement->execute([
                $item["food_qty"],
                $transactionId,
                $item["food_id"],
                $item["food_price"],
            ]);
        } finally {
            $statement->closeCursor();
        }

        return $status;
    }
}
