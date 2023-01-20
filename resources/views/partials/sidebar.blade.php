<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="/">SKU APP</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="/">SKU</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ request()->is('/') ? 'active' : '' }}"><a class="nav-link" href="/"><i
                        class="fas fa-fire"></i>
                    <span>Dashboard</span></a></li>
            <li class="menu-header">Menu</li>
            <li class="{{ request()->is('food/create') }}"><a class="nav-link" href="/food/create"><i
                        class="fas fa-plus"></i>
                    <span>Add</span></a></li>
            <li class="{{ request()->is('food/table') ? 'active' : '' }}"><a class="nav-link" href="/food/table"><i
                        class="fas fa-columns"></i>
                    <span>Table</span></a></li>
        </ul>
    </aside>
</div>