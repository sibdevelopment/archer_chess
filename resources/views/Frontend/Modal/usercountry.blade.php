<style>
    .modal-body {
        h1 {
            font-weight: 900;
            font-size: 2.3em;
            text-transform: uppercase;
        }

        a.pre-order-btn {
            color: #000;
            background-color: gold;
            border-radius: 1em;
            padding: 1em;
            display: block;
            margin: 2em auto;
            width: 50%;
            font-size: 1.25em;
            font-weight: 6600;

            &:hover {
                background-color: #000;
                text-decoration: none;
                color: gold;
            }
        }
    }
</style>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <!--         <h4 class="modal-title">Modal Header</h4> -->
            </div>
            <div class="modal-body p-3">
                <h1 class=" text-center">Welcome!</h1>
                <p class=" text-center">Please select your country from the list below to continue.</p>
                <form id="country-form" class="text-center">
                    @csrf
                    <div class="input-block ">
                        <label for="country">Country</label>
                        <select id="country" name="country" class="form-control">
                            <option value="" disabled selected>Select your country</option>
                            <option value="AUSTRALIA" @if ($country == 'AUSTRALIA') selected @endif>AUSTRALIA</option>
                            <option value="CANADA" @if ($country == 'CANADA') selected @endif>CANADA</option>
                            <option value="INDIA" @if ($country == 'INDIA') selected @endif>INDIA</option>
                            <option value="NEWZEALAND" @if ($country == 'NEWZEALAND') selected @endif>NEW ZEALAND</option>
                            <option value="SINGAPORE" @if ($country == 'SINGAPORE') selected @endif>SINGAPORE</option>
                            <option value="UAE" @if ($country == 'UAE') selected @endif>UAE</option>
                            <option value="UK" @if ($country == 'UK') selected @endif>UK</option>   
                            <option value="USA" @if ($country == 'USA') selected @endif>USA</option>
                            <option value="QATAR" @if ($country == 'QATAR') selected @endif>QATAR</option>
                            <option value="BAHRAIN" @if ($country == 'BAHRAIN') selected @endif>BAHRAIN</option>
                            <option value="KUWAIT" @if ($country == 'KUWAIT') selected @endif>KUWAIT</option>
                        </select>
                        <div id="country-error" style="color:red"></div>
                    </div>
                    <button type="submit" class="btn btn-submit text-center">Submit</button>
                </form>
            </div>  
            <div class="modal-footer">
                <div class="row w-100">
                    <div class="col-6 d-flex align-items-center justify-content-center">
                        <div class="iso-img-header text-center">
                            <img src="/frontend/tcul_img/iso-certification.jpg" alt="Img" class="img-fluid" style="width: 30%!important;">
                            <h6 class="mt-2 fw-bold" style="color: #0b4585; font-size: 12px;">ISO CERTIFIED</h6>
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-center">
                        <div class="iso-img-header text-center">
                            <img src="/frontend/tcul_img/fide.png" alt="Img" class="img-fluid" style="width: 75%!important; height: 61px !important;">
                            <h6 class="mt-2 fw-bold" style="color: #0b4585; font-size: 12px;">FIDE</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
