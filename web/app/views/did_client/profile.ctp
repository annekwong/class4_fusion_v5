<style>
    .row {
        margin-left: 0px;
    }

    .error {
        color: red;
    }

    div.grid-body {
        border: 1px solid #eee;
        padding: 10px 20px;
        margin-bottom: 15px;
        background: rgba(238, 238, 238, 0.22);
    }
</style>
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>User</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Profile</li>
</ul>

<div>
    <hr/>
</div>

<div class="innerLR">
    <!--NEW HTML-->
    <div class="row">
        <div class="col-md-12">
            <form id="profileForm" action="" method="post">
                <div class="grid simple">
                    <h4 style="width:50%;">Company Details </h4>
                    <p>Please keep your contact information up to date.</p>
                    <div class="grid-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="companyName">Company Name</label>
                                    <div class="controls">
                                        <input name="company" id="companyName" type="text" class="form-control" value="<?php echo $client['Client']['company']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Contact Details</h4>
                    <p>Please keep your contact information up to date. </p>
                    <div class="grid-body" style="margin-top:10px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="firstName">Name</label>
                                    <div class="controls">
                                        <input name="name" id="firstName" type="text" class="form-control" value="<?php echo $client['Client']['name']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="emailAddress">E-mail Address</label>
                                    <div class="controls">
                                        <input name="email" id="emailAddress" type="text" class="form-control" value="<?php echo $client['Client']['email']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="regPassword">Password</label>
                                    <div class="controls">
                                        <input name="password" id="password" type="password" class="form-control" minlength="6">
                                     </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="regPasswordConfirm">Confirm Password</label>
                                    <div class="controls">
                                        <input name="confirmPassword" id="confirmPassword" type="password" class="form-control" equalto="#regPassword" minlength="6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid-body" style="margin-top:10px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="control-group">
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <!--NEW HTML-->
</div>

<script src="<?php echo $this->webroot; ?>js/jquery.validate.min.js"></script>
<script src="<?php echo $this->webroot; ?>js/jquery.additional.validate.js"></script>
<script>
    $("#profileForm").validate({
        rules: {
            'confirmPassword': {
                equalTo: "#password"
            },
            'email': {
                email: true,
                remote: "<?php echo $this->webroot; ?>did_client/checkEmail"
            }
        },
        messages: {
            'confirmPassword': {
                equalTo: "Passwords don't match"
            },
            email: {
                remote: "This email is already in use"
            }
        }
    });
</script>