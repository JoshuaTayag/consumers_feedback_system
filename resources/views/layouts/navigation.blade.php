<ul class="navbar-nav">
  <!-- Dropdown -->
  <li class="nav-item dropdown">
    <a id="navbarPowerServe" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
      POWERSERVE
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarPowerServe">
      <li>
        <a class="dropdown-item" href="#">Action</a>
      </li>
      <li>
        <a class="dropdown-item" href="#">Another action</a>
      </li>
      <li>
        <a class="dropdown-item" href="#">
          HOUSE WIRING &raquo;
        </a>
        <ul class="dropdown-menu dropdown-submenu">
          <li>
            <a class="dropdown-item" href="{{ route('pre_membership_index') }}">PRE-MEMBERSHIP</a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('membership.index') }}">MEMBERSHIP</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">APPLICATION</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">Submenu item 3 &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="#">Multi level 1</a>
              </li>
              <li>
                <a class="dropdown-item" href="#">Multi level 2</a>
              </li>
            </ul>
          </li>
          
          <li>
            <a class="dropdown-item" href="#">Submenu item 5</a>
          </li>
        </ul>
      </li>
    </ul>
  </li>

  <li class="nav-item dropdown">
    <a id="navbarPowerPay" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
      POWERPAY
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarPowerPay">
      <li>
        <a class="dropdown-item" href="#">Action</a>
      </li>
      <li>
        <a class="dropdown-item" href="#">
          ANOTHER DROP &raquo;
        </a>
        <ul class="dropdown-menu dropdown-submenu">
          <li>
            <a class="dropdown-item" href="{{ route('pre_membership_index') }}">PRE-MEMBERSHIP</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">Submenu item 3 &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="#">Multi level 1</a>
              </li>
            </ul>
          </li>
          
          <li>
            <a class="dropdown-item" href="#">Submenu item 5</a>
          </li>
        </ul>
      </li>
    </ul>
  </li>

  <li class="nav-item dropdown">
    <a id="navbarPowerBill" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
      POWER BILL
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarPowerBill">
      <li>
        <a class="dropdown-item" href="{{ route('teller.index') }}">TELLER</a>
      </li>
      <li>
        <a class="dropdown-item" href="#">
          BILLING&raquo;
        </a>
        <ul class="dropdown-menu dropdown-submenu">
          <li>
            <a class="dropdown-item" href="{{ route('pre_membership_index') }}">PRE-MEMBERSHIP</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">Submenu item 3 &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="#">Multi level 1</a>
              </li>
            </ul>
          </li>
          
          <li>
            <a class="dropdown-item" href="#">Submenu item 5</a>
          </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>