<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| File Upload Constants
|--------------------------------------------------------------------------
|
| 파일이 업로드되는 서버경로, 파일의 서버 URL,
| 파일들이 업로드되는 폴더명들을 저장한다.
|
| 파일업로드 경로룰
| 회원 프로필 : UPLOAD_PATH/FOLDER_USER/년/월/일/
|
*/
defined('UPLOAD_PATH') OR define('UPLOAD_PATH', FCPATH . 'upload');
defined('UPLOAD_URL') OR define('UPLOAD_URL', 'upload' . DIRECTORY_SEPARATOR);

/*
|--------------------------------------------------------------------------
| Email Constants
|--------------------------------------------------------------------------
|
| Email 전송시 이용되는 관리자명, 관리자이메일
|
*/
defined('EMAIL_ADDR') OR define('EMAIL_ADDR', 'info@tfkint.co.kr');
defined('EMAIL_NAME') OR define('EMAIL_NAME', '베네픽쳐 관리자');

/*
|--------------------------------------------------------------------------
| Pagination Constants
|--------------------------------------------------------------------------
|
| 페이지네이션시 이용되는 Limit 갯수
|
*/
defined('LIMIT_10') OR define('LIMIT_10', 10);
defined('LIMIT_20') OR define('LIMIT_20', 20);

/*
|--------------------------------------------------------------------------
| Image Resize Constants
|--------------------------------------------------------------------------
|
| 이미지 리사이징에 이용되는 Constants
|
*/
defined('IMAGE_WIDTH') OR define('IMAGE_WIDTH', 250);
defined('IMAGE_HEIGHT') OR define('IMAGE_HEIGHT', 250);

/*
|--------------------------------------------------------------------------
| FCM Constants
|--------------------------------------------------------------------------
|
| 푸시알림 발송시 이용되는 Constants
|
*/
defined('PUSH_TYPE_KEYWORD') OR define('PUSH_TYPE_KEYWORD', 1);
defined('PUSH_TYPE_FRIEND') OR define('PUSH_TYPE_FRIEND', 2);
defined('PUSH_TYPE_WIN') OR define('PUSH_TYPE_WIN', 3);
defined('PUSH_TYPE_NOTICE') OR define('PUSH_TYPE_NOTICE', 4);
defined('PUSH_TYPE_APPLY') OR define('PUSH_TYPE_APPLY', 5);
defined('PUSH_TYPE_ADMIN') OR define('PUSH_TYPE_ADMIN', 6);
defined('PUSH_TYPE_PUZZLE') OR define('PUSH_TYPE_PUZZLE', 7);

/*
|--------------------------------------------------------------------------
| SMS Constants
|--------------------------------------------------------------------------
|
| SMS 발송시 이용되는 Constants
|
*/
defined('SMS_USER_ID') OR define('SMS_USER_ID', 'dnlemal123');
defined('SMS_USER_KEY') OR define('SMS_USER_KEY', 'BDkMPwg4Bz1QZVxzBjFQbAQ9UjILMAFvAG0DZQcxUjNXIQ==');
defined('SMS_CALLBACK_PHONE_NUMBER') OR define('SMS_CALLBACK_PHONE_NUMBER', '01049517087');

/*
|--------------------------------------------------------------------------
| DEV Constants
|--------------------------------------------------------------------------
|
| Development/Release Constants
|
*/
defined('DEV_MODE') OR define('DEV_MODE', false);

/*
|--------------------------------------------------------------------------
| API Constants
|--------------------------------------------------------------------------
|
| API Error Constants
|
*/
define('API_RES_SUCCESS', 0);
define('API_RES_ERR_PARAMETER', 1);
define('API_RES_ERR_DB', 2);
define('API_RES_ERR_INFO_NO_EXIST', 3);
define('API_RES_ERR_INCORRECT', 4);
define('API_RES_ERR_DUPLICATE', 5);
define('API_RES_ERR_PRIVILEGE', 6);
define('API_RES_ERR_FILE_UPLOAD', 7);
define('API_RES_ERR_UNKNOWN', 999);

/*
|--------------------------------------------------------------------------
| ALARM Constants
|--------------------------------------------------------------------------
|
| ALARM Type Constants
|
*/
defined('ALARM_FOLLOW') OR define('ALARM_FOLLOW', 1);						// 팔로우 신청
defined('ALARM_FOLLOW_ALLOW') OR define('ALARM_FOLLOW_ALLOW', 2);			// 팔로우 신청승인
defined('ALARM_LIKE') OR define('ALARM_LIKE', 3);							// 좋아요
defined('ALARM_COMMENT') OR define('ALARM_COMMENT', 4);					// 댓글
defined('ALARM_DOUBLE_COMMENT') OR define('ALARM_DOUBLE_COMMENT', 5);		// 대댓글
defined('ALARM_BOOKMARK') OR define('ALARM_BOOKMARK', 6);					// 북마크

/*
|--------------------------------------------------------------------------
| Device Constants
|--------------------------------------------------------------------------
|
| Device Type Constants
|
*/
defined('DEVICE_ANDROID') OR define('DEVICE_ANDROID', 1);
defined('DEVICE_IOS') OR define('DEVICE_IOS', 2);

/*
|--------------------------------------------------------------------------
| Login Type
|--------------------------------------------------------------------------
|
| Login Type Constants
|
*/
defined('LOGIN_PHONE') OR define('LOGIN_ID', 1);
defined('LOGIN_KAKAO') OR define('LOGIN_KAKAO', 2);


defined('STATUS_TRUE') OR define('STATUS_TRUE', 1);
defined('STATUS_FALSE') OR define('STATUS_FALSE', 0);

defined('STATUS_WAITING') OR define('STATUS_WAITING', 0); //대기
defined('STATUS_ALLOW') OR define('STATUS_ALLOW', 1);//승인
defined('STATUS_DENY') OR define('STATUS_DENY', 2);//거절

/*
 * 관리자 권한
 */

defined('ADMIN_SUPER') OR define('ADMIN_SUPER', '1'); // 최고관리자
defined('ADMIN_COMMON') OR define('ADMIN_COMMON', '2'); // 일반관리자
defined('ADMIN_ADVERTISE') OR define('ADMIN_ADVERTISE', '3'); // 광고주


/**
 * 관리자정보
 */

defined('ADMIN_ID')            OR define('ADMIN_ID', 'admin_id'); //관리자 아이디
defined('ADMIN_PRIVILEGE')          OR define('ADMIN_PRIVILEGE', 'admin_privilege'); //관리자 권한
defined('ADMIN_IDX')          OR define('ADMIN_IDX', 'admin_idx'); //광고주 식별정보

/**
 * 회원형태
 */
defined('USER_COMMON') OR define('USER_COMMON', 1); // 일반회원
defined('USER_ADVERTISE') OR define('USER_ADVERTISE', 2);  //광고주

/*
 * 회원상태
 */

defined('USER_NORMAL') OR define('USER_NORMAL', 1); // 정상회원
defined('USER_EXIT') OR define('USER_EXIT', 2); // 탈퇴회원

/*
 * 광고형태
 */

defined('ADVERTISE_GAME') OR define('ADVERTISE_GAME', 'G'); // 게임
defined('ADVERTISE_VIDEO') OR define('ADVERTISE_VIDEO', 'V'); // 비데오

/*
 * 광고상태
 */

defined('ADVERTISE_PAUSE') OR define('ADVERTISE_PAUSE', 0); // 일시정지
defined('ADVERTISE_NORMAL') OR define('ADVERTISE_NORMAL', 1); // 정상

/*
 * 게임형태
 */
defined('GAME_SWITCH') OR define('GAME_SWITCH', 'S'); // 스위칭퍼줄
defined('GAME_PAIR') OR define('GAME_PAIR', 'P'); // 짝맞추기

/*
 * 광고타겟성별
 */

defined('TARGET_GENDER_MALE') OR define('TARGET_GENDER_MALE', 'M'); // 남자
defined('TARGET_GENDER_FEMALE') OR define('TARGET_GENDER_FEMALE', 'F'); // 여자
defined('TARGET_GENDER_ALL') OR define('TARGET_GENDER_ALL', 'N'); // 상관없음

/*
 * term db설정
 */

defined('TERM_SERVICE_RULE') OR define('TERM_SERVICE_RULE', 1); // 서비스약관
defined('TERM_INFO_RULE') OR define('TERM_INFO_RULE', 2); // 정보약관
defined('TERM_ADVERTISE_RULE') OR define('TERM_ADVERTISE_RULE', 3); // 광고성약관
defined('TERM_ADVERTISE_COST') OR define('TERM_ADVERTISE_COST', 4); // 광고객단가