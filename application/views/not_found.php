<div id="body-wrap">
		<?php 

		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)){
		$dashboard_url=base_url().'organization/front-desk';

		}else if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==ORGANISATION_ADMINISTRATOR)){
			$dashboard_url=base_url().'organization/admin';
		}else if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==SYSTEM_ADMINISTRATOR)){
			$dashboard_url=base_url().'admin';
		}
		?>
                <section class="content">
                 
                    <div class="error-page">
                        <h2 class="headline text-info"> 404</h2>
                        <div class="error-content">
                            <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                            <p>
                                We could not find the page you were looking for. 
                                Meanwhile, you may <a href="<?php echo $dashboard_url; ?>">return to dashboard</a> 
                            </p>
                           
                        </div><!-- /.error-content -->
                    </div><!-- /.error-page -->

                </section><!-- /.content -->
</div>
