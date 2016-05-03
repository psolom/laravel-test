<div>
    <h3>Choose you country to get phone number</h3>
    <p class="help-block">
        Don't forget to click "Get Number!" button.
    </p>
</div>
<form id="country-form" role="form">
    <div class="form-group">
        <div id="countries-list"></div>
        <button type="submit" class="btn btn-default">
            Get Number!
        </button>
    </div>
    <input type="hidden" id="country-code">
    <input type="hidden" id="csrf-token" name="_token" value="<?php echo csrf_token(); ?>">
</form>