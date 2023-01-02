 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
     <div class="container-fluid">
         <!-- Siderbar button -->
         <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
             aria-controls="offcanvasExample">
             <span class="navbar-toggler-icon" data-bs-target="#offcanvasExample"></span>
         </button>
         <!-- End Sidebar button -->
         <a class="navbar-brand fw-bold text-uppercase me-auto" href="#">{{ SettingHelper::get('name') }}</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
             data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
             aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">

             {{-- <form class="d-flex ms-auto" role="search">
                 <div class="input-group my-lg-0 my-3">
                     <input type="text" class="form-control" placeholder="Recipient's username"
                         aria-label="Recipient's username" aria-describedby="button-addon2" />
                     <button class="btn btn-primary" type="button" id="button-addon2">
                         <i class="bi bi-search"></i>
                     </button>
                 </div>
             </form> --}}
             <div class="d-flex ms-auto text-white">{{ auth('web')->user()->fname }} {{ auth('web')->user()->lname }}
             </div>
             <ul class="navbar-nav mb-lg-0 mb-2">
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                         <i class="bi bi-people-fill"></i>
                     </a>
                     <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                         <li><a class="dropdown-item" href="#">Action</a></li>
                         <li><a class="dropdown-item" href="#">Another action</a></li>
                         <li>
                             <hr class="dropdown-divider" />
                         </li>
                         <li>
                             <a href="{{route('logout')}}" class="dropdown-item" href="#">Logout</a>
                         </li>
                     </ul>
                 </li>
             </ul>
         </div>
     </div>
 </nav>
 <!-- End Navbar -->
