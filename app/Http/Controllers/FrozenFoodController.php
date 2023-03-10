<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrozenFoodRequest;
use App\Http\Requests\FrozenFoodSearchRequest;
use App\Modules\FrozenFood\FrozenFoodService;
use Exception;
use Illuminate\Http\Request;

class FrozenFoodController extends Controller
{
    private FrozenFoodService $service;

    public function __construct(FrozenFoodService $service)
    {
        $this->service = $service;
    }

    public function table(Request $request)
    {
        $foods = $this->service->getPaginatedData(5);
        $foods->setPath('/food/table');
        return view('pages.frozenfood.table', compact('foods'));
    }

    public function addFormInput()
    {
        return view("pages.frozenfood.add");
    }

    public function add(FrozenFoodRequest $request)
    {
        $data = $request->validated();
        try {
            $this->service->createNewFroozenFood($data);
            return redirect()->back()->with("success", "Data has been successfully created");
        } catch (Exception $err) {
            return redirect()->back()->with("error", $err->getMessage());
        }
    }

    public function updateFormInput($id)
    {
        try {
            $data = $this->service->getDataById($id);
            return view("pages.frozenfood.update", ["food" => $data]);
        } catch(Exception $err) {
            return redirect()->back()->with("error", $err->getMessage());
        }
    }

    public function update(FrozenFoodRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $this->service->update($data, $id);
            return redirect()->back()->with("success", "Data successfully updated");
        } catch (Exception $err) {
            return redirect()->back()->with("error", $err->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->service->softDelete($id);
            return redirect()->back()->with("success", "Data successfully deleted");
        } catch (Exception $err) {
            return redirect()->back()->with("error", $err->getMessage());
        }
    }

    public function search(FrozenFoodSearchRequest $request)
    {
        $foods = $this->service->searchByColName($request->validated());
        $foods->setPath('/food/table');
        return view('pages.frozenfood.table', compact('foods'));
    }
}
