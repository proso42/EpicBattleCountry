var f = document.getElementById('send_reset_password_email_form');
f.addEventListener('submit', function(e)
{
    e.preventDefault();
    var btn = document.getElementById('reset-button');
    btn.disabled = true;
    btn.style.display = 'none';
    var spin = document.getElementById('spin');
    spin.style.display = '';
    setTimeout(() =>{
        document.getElementById('send_reset_password_email_form').submit();
    }, 1500);
});