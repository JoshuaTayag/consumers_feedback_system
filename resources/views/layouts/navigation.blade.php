<ul class="navbar-nav">
  <!-- Dropdown -->
  <li class="nav-item dropdown">
    <a id="navbarPowerServe" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
      POWERSERVE
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarPowerServe">
      <li>
        <a class="dropdown-item" href="#">
          HOUSE WIRING &raquo;
        </a>
        <ul class="dropdown-menu dropdown-submenu">
          <li>
            <a class="dropdown-item" href="{{ route('pre_membership_index') }}">PRE-MEMBERSHIP</a>
          </li>

          <!-- <li>
            <a class="dropdown-item" href="#">PRE-MEMBERSHIP &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="{{ route('lifeline.index') }}">APPLY FOR LIFELINE</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('lifeline.report') }}">REPORTS</a>
              </li>
            </ul>
          </li> -->

          <li>
            <a class="dropdown-item" href="{{ route('membership.index') }}">MEMBERSHIP</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">SERVICE CONNECT ORDER &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="{{ route('service-connect-order.index') }}">REGULAR</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('indexCM') }}">CHANGE METER</a>
              </li>
            </ul>
          </li>
          {{-- <li>
            <a class="dropdown-item" href="{{ route('lifeline.index') }}">APPLY FOR LIFELINE</a>
          </li> --}}
          <li>
            <a class="dropdown-item" href="#">LIFELINE &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="{{ route('lifeline.index') }}">APPLY FOR LIFELINE</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('lifeline.report') }}">REPORTS</a>
              </li>
            </ul>
          </li>
          <li>
            <a class="dropdown-item" href="#">BRGY ELECTRICIANS &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="{{ route('electrician.index') }}">NEW/RENEWAL OF APPLICATION</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('electricianComplaintIndex') }}">COMPLAINTS</a>
              </li>
            </ul>
          </li>
        </ul>
      </li>

      <li>
        <a class="dropdown-item" href="#">
          BILLING &raquo;
        </a>
        <ul class="dropdown-menu dropdown-submenu">
          <li>
            <a class="dropdown-item" href="{{ route('pre_membership_index') }}">LEDGER</a>
          </li>
        </ul>
      </li>
    </ul>
  </li>

  {{-- <li class="nav-item dropdown">
    <a id="navbarPowerPay" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
      POWERPAY
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarPowerPay">
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
        </ul>
      </li>
    </ul>
  </li> --}}

  {{-- <li class="nav-item dropdown">
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
  </li> --}}

  <li class="nav-item dropdown">
    <a id="navbarPowerBill" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
      POWER HOUSE
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarPowerBill">
      <li>
        <a class="dropdown-item" href="#">
          PURCHASING&raquo;
        </a>
        <!-- <ul class="dropdown-menu dropdown-submenu">
          <li>
            <a class="dropdown-item" href="{{ route('pre_membership_index') }}">CANVASS</a>
          </li>
        </ul> -->
      </li>
      <li>
        <a class="dropdown-item" href="#">
          WAREHOUSING&raquo;
        </a>
        <ul class="dropdown-menu dropdown-submenu">
          <li>
            <a class="dropdown-item" href="{{ route('material-requisition-form.index') }}">MER</a>
          </li>
          <li>
            <a class="dropdown-item" href="#">DATA MANAGEMENT &raquo; </a>
            <ul class="dropdown-menu dropdown-submenu">
              <li>
                <a class="dropdown-item" href="{{ route('structure.index') }}">STRUCTURE</a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>