/**+++++++++++++++++++++++++++++++++++++
 *
 * Constants
 *
 +++++++++++++++++++++++++++++++++++++++*/

const CONFIRM_SAVE = 1;
const CONFIRM_DELETE = 2;
const CONFIRM_WITHDRAW = 3;
const CONFIRM_DELETE_NOTICE = 4;
const CONFIRM_PAUSE = 5;
const CONFIRM_TO_ADVERTISE = 6;
const CONFIRM_TO_COMMON_USER = 7;

/**+++++++++++++++++++++++++++++++++++++
 *
 * 서버통신시 로딩바 현시 메서드들
 *
 +++++++++++++++++++++++++++++++++++++++*/

function showBlockUI(target) {
    if (target === $('#pageContent')) {
        target = null;
    }

    App.blockUI({
        animate: !0,
        target: target
        // cenrerY: true
    });
}

function hideBlockUI(target) {
    window.setTimeout(function () {
        App.unblockUI(target);
    }, 500);
}

/**+++++++++++++++++++++++++++++++++++++
 *
 * Alert, Confirm 현시 메서드들
 *
 +++++++++++++++++++++++++++++++++++++++*/

function showConfirm(type, callback) {

    let strOkLabel = '확인';
    let strCancelLabel = '취소';
    let strConfirmLabel = '확인';
    let msg = '';

    switch (type) {
        case CONFIRM_SAVE:
            msg = '내용을 저장하시겠습니까?';
            break;
        case CONFIRM_DELETE:
            msg = '삭제하시겠습니까?';
            break;
        case CONFIRM_DELETE_NOTICE:
            msg = '이 글을 삭제하시겠습니까?';
            break;
        case CONFIRM_WITHDRAW:
            msg = '선택한 회원 강퇴를 진행하시겠습니까?';
            break;
        case CONFIRM_PAUSE:
            msg = '선택한 광고를 일시정지하시겠습니까?';
            break;
        case CONFIRM_TO_ADVERTISE:
            msg = '선택한 회원을 광고주로 변경하시겠습니까?';
            break;
        case CONFIRM_TO_COMMON_USER:
            msg = '선택한 회원을 일반회원로 변경하시겠습니까?';
            break;
        default:
            msg = '';
    }

    bootbox.addLocale('ko', {'OK': strOkLabel, 'CANCEL': strCancelLabel, 'CONFIRM': strConfirmLabel});
    bootbox.setLocale('ko');

    bootbox.confirm(msg, (agree) => {
        if (agree) {
            if (typeof callback !== 'undefined') {
                callback();
            }
        }
    });
}

function serverErrMsg() {
    return '서버상태가 불안정합니다. 잠시후 다시 시도해주세요';
}

function showToast(class_name, message, title) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "300",
        "timeOut": "2500",
        "extendedTimeOut": "500",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr[class_name](message, title);
}

/**+++++++++++++++++++++++++++++++++++++
 *
 * 페이지이행메서드
 *
 +++++++++++++++++++++++++++++++++++++++*/

function go_to_url(path, params, method) {
    method = method || "GET";  //method 부분은 입력안하면 자동으로 get 가 된다.

    let form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    //input type hidden name(key) value(params[key]);
    for (let key in params) {
        let hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }

    document.body.appendChild(form);
    form.submit();
}