<template>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
							<div class="x_panel">
								<div class="x_title">
									<h2>Form Design <small>different form elements</small></h2>
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
											<ul class="dropdown-menu" role="menu">
												<li><a class="dropdown-item" href="#">Settings 1</a>
												</li>
												<li><a class="dropdown-item" href="#">Settings 2</a>
												</li>
											</ul>
										</li>
										<li><a class="close-link"><i class="fa fa-close"></i></a>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<br>
									<form id="demo-form2" data-p-validate="" class="form-horizontal form-label-left" novalidate="">

										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">First Name <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
												<input type="text" id="first-name" required="required" class="form-control ">
											</div>
										</div>
										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Last Name <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
												<input type="text" id="last-name" name="last-name" required="required" class="form-control">
											</div>
										</div>
										<div class="item form-group">
											<label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Middle Name / Initial</label>
											<div class="col-md-6 col-sm-6 ">
												<input id="middle-name" class="form-control" type="text" name="middle-name">
											</div>
										</div>
										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align">Gender</label>
											<div class="col-md-6 col-sm-6 ">
												<div id="gender" class="btn-group" data-toggle="buttons">
													<label class="btn btn-secondary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
														<input type="radio" name="gender" value="male" class="join-btn" data-p-multiple="gender"> &nbsp; Male &nbsp;
													</label>
													<label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
														<input type="radio" name="gender" value="female" class="join-btn" data-p-multiple="gender"> Female
													</label>
												</div>
											</div>
										</div>
										<div class="item form-group ">
											<label class="col-form-label col-md-3 col-sm-3 label-align">Date Of Birth <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
												<input id="birthday" class=" p-error date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
												<ul class="p-errors-list filled" id="p-id-38"><li class="p-required">This value is required.</li></ul>
											</div>
										</div>
                                        <div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">@</span>
  <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
</div>

<label for="basic-url" class="form-label">Your vanity URL</label>
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon3">https://example.com/users/</span>
  <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
</div>
                                        <p>

												<label for="heard">Heard us by *:</label>
												<select id="heard" class="form-control p-error" required="" data-p-id="38">
													<option value="">Choose..</option>
													<option value="press">Press</option>
													<option value="net">Internet</option>
													<option value="mouth">Word of mouth</option>
												</select><ul class="p-errors-list filled" id="p-id-38"><li class="p-required">This value is required.</li></ul>

												<label for="message">Message (20 chars min, 100 max) :</label>
												<textarea id="message" required="required" class="form-control p-error" name="message" data-p-trigger="keyup" data-p-minlength="20" data-p-maxlength="100" data-p-minlength-message="Come on! You need to enter at least a 20 caracters long comment.." data-p-validation-threshold="10" data-p-id="40"></textarea><ul class="p-errors-list filled" id="p-id-40"><li class="p-required">This value is required.</li></ul>

												<br>
												<span class="btn btn-primary">Validate form</span>

									</p>
										<div class="ln_solid"></div>
										<div class="item form-group">
											<div class="col-md-6 col-sm-6 offset-md-3">
												<button class="btn btn-primary" type="button">Cancel</button>
												<button class="btn btn-primary" type="reset">Reset</button>
												<button type="submit" class="btn btn-success">Submit</button>
											</div>
										</div>

									</form>
								</div>
							</div>
						</div>
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Table design <small>Custom design</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link"
                                ><i class="fa fa-chevron-up"></i
                            ></a>
                        </li>
                        <li class="dropdown">
                            <a
                                href="#"
                                class="dropdown-toggle"
                                data-toggle="dropdown"
                                role="button"
                                aria-expanded="false"
                                ><i class="fa fa-wrench"></i
                            ></a>
                            <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton"
                            >
                                <a class="dropdown-item" href="#">Settings 1</a>
                                <a class="dropdown-item" href="#">Settings 2</a>
                            </div>
                        </li>
                        <li>
                            <a class="close-link"
                                ><i class="fa fa-close"></i
                            ></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <p>
                        Add class <code>bulk_action</code> to table for bulk
                        actions options on row select
                    </p>

                    <div class="table-responsive">
                        <table
                            class="table table-striped jambo_table bulk_action"
                        >
                            <thead>
                                <tr class="headings">
                                    <th>
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                id="check-all"
                                                class="flat"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </th>
                                    <th class="column-title">Invoice</th>
                                    <th class="column-title">Invoice Date</th>
                                    <th class="column-title">Order</th>
                                    <th class="column-title">Bill to Name</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Amount</th>
                                    <th class="column-title no-link last">
                                        <span class="nobr">Action</span>
                                    </th>
                                    <th class="bulk-actions" colspan="7">
                                        <a
                                            class="antoo"
                                            style="
                                                color: #fff;
                                                font-weight: 500;
                                            "
                                            >Bulk Actions (
                                            <span class="action-cnt"> </span> )
                                            <i class="fa fa-chevron-down"></i
                                        ></a>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr class="even pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000040</td>
                                    <td class="">May 23, 2014 11:47:56 PM</td>
                                    <td class="">
                                        121000210
                                        <i
                                            class="success fa fa-long-arrow-up"
                                        ></i>
                                    </td>
                                    <td class="">John Blank L</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$7.45</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="odd pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000039</td>
                                    <td class="">May 23, 2014 11:30:12 PM</td>
                                    <td class="">
                                        121000208
                                        <i
                                            class="success fa fa-long-arrow-up"
                                        ></i>
                                    </td>
                                    <td class="">John Blank L</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$741.20</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="even pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000038</td>
                                    <td class="">May 24, 2014 10:55:33 PM</td>
                                    <td class="">
                                        121000203
                                        <i
                                            class="success fa fa-long-arrow-up"
                                        ></i>
                                    </td>
                                    <td class="">Mike Smith</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$432.26</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="odd pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000037</td>
                                    <td class="">May 24, 2014 10:52:44 PM</td>
                                    <td class="">121000204</td>
                                    <td class="">Mike Smith</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$333.21</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="even pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000040</td>
                                    <td class="">May 24, 2014 11:47:56 PM</td>
                                    <td class="">121000210</td>
                                    <td class="">John Blank L</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$7.45</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="odd pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000039</td>
                                    <td class="">May 26, 2014 11:30:12 PM</td>
                                    <td class="">
                                        121000208
                                        <i
                                            class="error fa fa-long-arrow-down"
                                        ></i>
                                    </td>
                                    <td class="">John Blank L</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$741.20</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="even pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000038</td>
                                    <td class="">May 26, 2014 10:55:33 PM</td>
                                    <td class="">121000203</td>
                                    <td class="">Mike Smith</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$432.26</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="odd pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000037</td>
                                    <td class="">May 26, 2014 10:52:44 PM</td>
                                    <td class="">121000204</td>
                                    <td class="">Mike Smith</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$333.21</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>

                                <tr class="even pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000040</td>
                                    <td class="">May 27, 2014 11:47:56 PM</td>
                                    <td class="">121000210</td>
                                    <td class="">John Blank L</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$7.45</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                                <tr class="odd pointer">
                                    <td class="a-center">
                                        <div
                                            class="icheckbox_flat-green"
                                            style="position: relative"
                                        >
                                            <input
                                                type="checkbox"
                                                class="flat"
                                                name="table_records"
                                                style="
                                                    position: absolute;
                                                    opacity: 0;
                                                "
                                            /><ins
                                                class="iCheck-helper"
                                                style="
                                                    position: absolute;
                                                    top: 0%;
                                                    left: 0%;
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    margin: 0px;
                                                    padding: 0px;
                                                    background: rgb(
                                                        255,
                                                        255,
                                                        255
                                                    );
                                                    border: 0px;
                                                    opacity: 0;
                                                "
                                            ></ins>
                                        </div>
                                    </td>
                                    <td class="">121000039</td>
                                    <td class="">May 28, 2014 11:30:12 PM</td>
                                    <td class="">121000208</td>
                                    <td class="">John Blank L</td>
                                    <td class="">Paid</td>
                                    <td class="a-right a-right">$741.20</td>
                                    <td class="last"><a href="#">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</template>

<script>
export default {};
</script>

