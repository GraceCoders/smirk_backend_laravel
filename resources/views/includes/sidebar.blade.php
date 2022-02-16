<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('dashboard') }}" aria-expanded="false"><i
                            class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('users.list') }}" aria-expanded="false"><i class="mdi mdi-account"></i><span
                            class="hide-menu">Users</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('ethnicities.list') }}" aria-expanded="false"><i
                            class="mdi mdi-chart-bubble"></i><span class="hide-menu">Ethnicity</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('preferences.list') }}" aria-expanded="false"><i
                            class="mdi mdi-border-inside"></i><span class="hide-menu">Preferences</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('shows.list') }}" aria-expanded="false"><i
                            class="mdi mdi-television"></i><span class="hide-menu">Shows</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('cards.list') }}" aria-expanded="false"><i
                            class="mdi mdi-cards"></i><span class="hide-menu">Cards</span></a></li>


                            <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('category.list.index') }}" aria-expanded="false"><i
                                    class="mdi mdi-bitbucket"></i><span class="hide-menu">Catgory</span></a></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
