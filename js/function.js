switchqrcode = function () {
    if (document.getElementById('qrcode').innerHTML == "远程访问") {
        document.getElementById('qrcode_img').style.display = "block";
        document.getElementById('qrcode').innerHTML = "请扫描二维码";
    } else {
        document.getElementById('qrcode_img').style.display = "none";
        document.getElementById('qrcode').innerHTML = "远程访问";
    }
};
