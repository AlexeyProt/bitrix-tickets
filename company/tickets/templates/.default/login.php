<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<content>
    <div class="login">
        <? $APPLICATION->IncludeComponent(
            "bitrix:system.auth.form",
            "auth",
            array(
                "FORGOT_PASSWORD_URL" => "",
                "PROFILE_URL" => $arResult['LIST_PAGE_URL'],
                "REGISTER_URL" => "",
                "SHOW_ERRORS" => "N"
            )
        ); ?>
    </div>
</content>


<script type="text/javascript">
    $('body').on('click', '#passwordToggle_eye', function () {
        if ($('#passwordInput').attr('type') == 'password') {

            $('#passwordToggle_eye').addClass('fa fa-eye-slash');
            $('#passwordToggle_eye').removeClass('fa fa-eye');
            console.log('here we are');
            $('#passwordInput').attr('type', 'text');
        } else {
            $(this).removeClass('show');
            $('#passwordToggle_eye').addClass('fa fa-eye');
            $('#passwordToggle_eye').removeClass('fa fa-eye-slash');
            console.log('no');

            $('#passwordInput').attr('type', 'password');
        }
        return false;
    });
</script>