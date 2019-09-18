NDITION</option> -->
                                            <option value="consent to exam obtained">CONSENT TO EXAM OBTAINED</option>
                                            <option value="unable to consent">UNABLE TO CONSENT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 ">
                                <div class="form-group">
                                    <div class="input-group w3_w3layouts col-lg-12">
                                        <span class="input-group-addon" id="basic-addon1">STAFF SIGNATURE</span>
                                        <input type="text" name="signature" class="form-control" value="<?php echo e(\Auth::User()->name); ?>" placeholder="" readonly="" aria-describedby="basic-addon1" required="" / >
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 ">
                                <div class="form-group">
                                    <div class="input-group w3_w3layouts col-lg-12">
                                        <span class="input-group-addon" id="basic-addon1">DATE</span>
                                        <input type="text" name="date" class="form-control"  value="<?php echo e(date('d/m/Y')); ?>" aria-describedby="basic-addon1" required="" readonly / >
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 ">
                                <div class="form-group">
                         