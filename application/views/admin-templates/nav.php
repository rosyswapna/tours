     
            <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <?php if($this->session->userdata('isLoggedIn')==null || $this->session->userdata('isLoggedIn')!=true) {?>
                        <li class="active">
                            <a href="<?php echo base_url();?>">
                                <i class="fa fa-home"></i> <span> Home </span>
                            </a>
                        </li>
                        <?php } else if($this->session->userdata('isLoggedIn')==true && $this->session->userdata('type')==SYSTEM_ADMINISTRATOR){ ?>
                        <li>
                            <a href="<?php echo base_url().'admin';?>">
                                <i class="fa fa-home"></i> <span> Dashboard </span>
                            </a>
                        </li>
                        
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-users"></i>
                                <span>Organizations</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url().'admin/organization/new';?>"><i class="fa fa-user"></i>Add Organizations</a></li>
                                <li><a href="<?php echo base_url().'admin/organization/list';?>"><i class="fa fa-users"></i>List Organizations</a></li>
                                
                            </ul>
                        </li>
                        <?php }else if($this->session->userdata('isLoggedIn')==true && $this->session->userdata('type')==ORGANISATION_ADMINISTRATOR){ ?>
                        <li>
                            <a href="<?php echo base_url().'organization/admin';?>">
                                <i class="fa fa-home"></i> <span> Dashboard </span>
                            </a>
                        </li>
                        

			<li class="treeview">
                            <a href="#">
                                <i class="fa fa-wrench"></i>
                                <span>Accounts</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
				 <li><a href="<?php echo base_url().'account/organization/CompanySetup';?>"><i class="fa fa-angle-double-right"></i> Company Setup</a></li>
                                <li><a href="<?php echo base_url().'account/organization/DisplaySetup';?>"><i class="fa fa-angle-double-right"></i> Display Setup</a></li>
				 <li><a href="<?php echo base_url().'account/organization/AccessSetup';?>"><i class="fa fa-angle-double-right"></i> Access Setup</a></li>
				<li><a href="<?php echo base_url().'account/organization/FormSetup';?>"><i class="fa fa-angle-double-right"></i> Forms Setup</a></li>
				
	
				<li><a href="<?php echo base_url().'account/organization/PaymentTerms';?>"><i class="fa fa-angle-double-right"></i> Payment Terms</a></li>
				<li><a href="<?php echo base_url().'account/organization/GlAccount';?>"><i class="fa fa-angle-double-right"></i> GL Accounts</a></li>
				<li><a href="<?php echo base_url().'account/organization/TaxType';?>"><i class="fa fa-angle-double-right"></i> Tax Types</a></li>
				<li><a href="<?php echo base_url().'account/organization/TaxGroups';?>"><i class="fa fa-angle-double-right"></i> Tax Group</a></li>
				<li><a href="<?php echo base_url().'account/organization/FiscalYear';?>"><i class="fa fa-angle-double-right"></i> Fiscal Year</a></li>
				<li><a href="<?php echo base_url().'account/organization/SystemGl';?>"><i class="fa fa-angle-double-right"></i> System and GL Setup</a></li>
				<li><a href="<?php echo base_url().'account/organization/VoidTransaction';?>"><i class="fa fa-angle-double-right"></i> Void Transaction</a></li>
				<li><a href="<?php echo base_url().'account/organization/Backup';?>"><i class="fa fa-angle-double-right"></i> Backup and Restore</a></li>
                                
                                
                            </ul>
                        </li>
			
			
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-users"></i>
                                <span>Users</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url().'organization/admin/front-desk/new';?>"><i class="fa fa-user"></i> Add  Users</a></li>
                                <li><a href="<?php echo base_url().'organization/admin/front-desk/list';?>"><i class="fa fa-users"></i> List Users</a></li>
                                
                            </ul>
                        </li>
                        <?php }else if($this->session->userdata('isLoggedIn')==true && $this->session->userdata('type')==FRONT_DESK){ ?>
                        <li>
                            <a href="<?php echo base_url().'organization/front-desk';?>">
                                <i class="fa fa-home"></i> <span> Dashboard </span>
                            </a>
                        </li>
                        
		
		

			<li class="treeview">
                            <a href="#">
                                <i class="fa fa-user-md"></i>
                                <span>Driver</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                               
                                <li><a href=" <?php echo base_url().'organization/front-desk/list-driver';?>"><i class="fa fa-angle-double-right"></i>Manage Drivers</a></li>
                                
                            </ul>
                        </li>
						<li class="treeview">
                            <a href="#">
                                <i class="fa fa-truck"></i>
                                <span>Vehicle</span><i class="fa fa-angle-left pull-right"></i>
								<ul class="treeview-menu">
                                
                                <li><a href=" <?php echo base_url().'organization/front-desk/list-vehicle';?>"><i class="fa fa-angle-double-right"></i>Manage Vehicles</a></li>
                                
                            </ul>
                                
                            </a>
							<!--<li>
                            <a href="<?php echo base_url().'organization/front-desk/device';?>">
                                <i class="fa fa-laptop"></i> <span> Device </span>
                            </a>
                       	 </li>-->
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url();?>"><i class="fa fa-angle-double-right"></i>Trip Booking</a></li>
                                
                                
                            </ul>
                        </li>
						<li class="treeview">
                            <a href="#">
                                <i class="fa fa-users"></i>
                                <span>Customer</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().'organization/front-desk/customers';?>"><i class="fa fa-angle-double-right"></i>Manage Customers</a></li> 
                                
                            </ul>
                        </li>
						
						<li class="treeview">
                            <a href="#">
                                <i class="fa fa-exchange"></i>
                                <span>Trip</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url().'organization/front-desk/trip-booking';?>"><i class="fa fa-angle-double-right"></i>New Trip</a></li>
						<li><a href="<?php echo base_url().'organization/front-desk/trips';?>"><i class="fa fa-angle-double-right"></i>Trip Bookings</a></li>
                          <li><a href="<?php echo base_url().'organization/front-desk/tripvouchers';?>"><i class="fa fa-angle-double-right"></i>Trip Vouchers</a></li>      
                                
                            </ul>
                        </li>
						
							<li class="treeview">
                            <a href="#">
                                <i class="fa fa-book"></i>
                                <span>Accounts</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
				<li><a href="<?php echo base_url().'account/front_desk/JournelEntry';?>"><i class="fa fa-angle-double-right"></i>Journel Entry</a></li>
				 
                                <li><a href="<?php echo base_url().'account/front_desk/SalesDeliveries';?>"><i class="fa fa-angle-double-right"></i> Trip Deliveries</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/SalesInvoices';?>"><i class="fa fa-angle-double-right"></i> Trip Invoice</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/NewBankPayment';?>"><i class="fa fa-angle-double-right"></i> Payment</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/NewBankDeposit';?>"><i class="fa fa-angle-double-right"></i> Receipt</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/BankTransfer';?>"><i class="fa fa-angle-double-right"></i> Bank Account Transfers</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/ReconcileBank';?>"><i class="fa fa-angle-double-right"></i> Reconcile Bank Account</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/CustomerTransactions';?>"><i class="fa fa-angle-double-right"></i> Customer Transactions</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/DriverTransactions';?>"><i class="fa fa-angle-double-right"></i> Driver Transactions</a></li>
				<li><a href="<?php echo base_url().'account/front_desk/OwnerTransactions';?>"><i class="fa fa-angle-double-right"></i> Vehicle Owner Transactions</a></li>
				
                                
                                
                            </ul>
                        </li>
	
			<li class="treeview">
                            <a href="#">
                                <i class="fa  fa-credit-card"></i>
                                <span>Account Statements</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
				 <li><a href="<?php echo base_url().'account/front_desk/TrialBalance';?>"><i class="fa fa-angle-double-right"></i> Trial Balance</a></li>
                                <li><a href="<?php echo base_url().'account/front_desk/BalanceSheet';?>"><i class="fa fa-angle-double-right"></i> Balance sheet</a></li>
				 <li><a href="<?php echo base_url().'account/front_desk/ProfitLoss';?>"><i class="fa fa-angle-double-right"></i> Profit and Loss</a></li>                                
                                
                            </ul>
                        </li>


                        
			<li class="treeview">
                            <a href="#">
                                <i class="fa fa-wrench"></i>
                                <span> Settings</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url().'organization/front-desk/settings';?>"><i class="fa fa-angle-double-right"></i>General Settings</a></li>
                                <li class="treeview">
		                    <a href="#">
		                        <i class="fa fa-wrench"></i>
		                        <span>Tariff Settings</span>
		                        <i class="fa fa-angle-left pull-right"></i>
		                    </a>
		                    <ul class="treeview-menu">
		                        <li><a href="<?php echo base_url().'organization/front-desk/tarrif-masters';?>"><i class="fa fa-angle-double-right"></i> Tariff Masters</a></li>
		                        <li><a href="<?php echo base_url().'organization/front-desk/tarrif';?>"><i class="fa fa-angle-double-right"></i>Tariffs</a></li>
		                        
		                    </ul>
                        	</li>
							 <li><a href="<?php  echo base_url().'organization/front-desk/device';?>"><i class="fa fa-angle-double-right"></i>Device Settings</a></li>

				<li class="treeview">
		                    <a href="#">
		                        <i class="fa fa-angle-double-right"></i>
		                        <span>Account Settings</span>
		                        <i class="fa fa-angle-left pull-right"></i>
		                    </a>
		                    <ul class="treeview-menu">
		                        <li><a href="<?php echo base_url().'account/front_desk/BankAccounts';?>"><i class="fa fa-angle-double-right"></i> Bank Accounts</a></li>
		                        <li><a href="<?php echo base_url().'account/front_desk/GlAccounts';?>"><i class="fa fa-angle-double-right"></i>GL Accounts</a></li>
					<li><a href="<?php echo base_url().'account/front_desk/GlAccountGroups';?>"><i class="fa fa-angle-double-right"></i>GL Account Groups</a></li>
					<li><a href="<?php echo base_url().'account/front_desk/GlAccountClasses';?>"><i class="fa fa-angle-double-right"></i>GL Account Classes</a></li>
		                        
		                    </ul>
                        	</li>
                                
                            </ul>
                        </li>

                        <?php }else if($this->session->userdata('isLoggedIn')==true && $this->session->userdata('type')==CUSTOMER){ ?>
                        <li>
                            <a href="<?php echo base_url().'customer/home';?>">
                                <i class="fa fa-home"></i> <span> Dashboard </span>
                            </a>
                        </li>

			<li>
			    <a href="<?php echo base_url().'organization/front-desk/customer/'.$this->session->userdata('customer')->id;?>">
				<i class="fa fa-angle-double-right"></i>Manage Profile
			    </a>
			</li>

			<li class="treeview">
                            <a href="#">
                                <i class="fa fa-exchange"></i>
                                <span>Trip</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url().'organization/front-desk/trip-booking';?>"><i class="fa fa-angle-double-right"></i>New Trip</a></li>
				<li><a href="<?php echo base_url().'organization/front-desk/trips';?>"><i class="fa fa-angle-double-right"></i>Trip Bookings</a></li>

                            </ul>
                        </li>
			<?php }else if($this->session->userdata('isLoggedIn')==true && $this->session->userdata('type')==DRIVER){ ?>
                        <li>
                            <a href="<?php echo base_url().'driver/home';?>">
                                <i class="fa fa-home"></i> <span> Dashboard </span>
                            </a>
                        </li>

			<li>
			    <a href="<?php echo base_url().'organization/front-desk/driver-profile/'.$this->session->userdata('driver')->id;?>">
				<i class="fa fa-angle-double-right"></i>Manage Profile
			    </a>
			</li>

			<li class="treeview">
                            <a href="#">
                                <i class="fa fa-exchange"></i>
                                <span>Trip</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
				<li><a href="<?php echo base_url().'organization/front-desk/trips';?>"><i class="fa fa-angle-double-right"></i>Trip Bookings</a></li>
				<li><a href="<?php echo base_url().'organization/front-desk/tripvouchers';?>"><i class="fa fa-angle-double-right"></i>Trip Vouchers</a></li>
                            </ul>
                        </li>
			<?php } else if($this->session->userdata('isLoggedIn')==true && $this->session->userdata('type')==VEHICLE_OWNER){ ?>
                        <li>
                            <a href="<?php echo base_url().'vehicle/home';?>">
                                <i class="fa fa-home"></i> <span> Dashboard </span>
                            </a>
                        </li>

			

			
			<?php } ?>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
        	<aside class="right-side" id ="voucher-stretch">
