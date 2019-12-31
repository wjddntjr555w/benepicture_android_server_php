CodeIgniter에서 namespace 기능을 리용할수 있도록 지원하는 vendor입니다.
CodeIgniter가 표준으로 namespace를 지원하지 않으므로 CI를 리용하는 과정에 namespace를 리용하려면 추가적인 조작이 필요합니다.

compose.json파일에 다음의 행을 추가합니다.

  "autoload": {
	"psr-4": {
	  "app\\": "application"
	}
  },
  
다음 application/config/config.php에 composer_autoload  를 설정해 줍니다.

   $config['composer_autoload'] = 'vendor/autoload.php';
   
우의 설정을 진행하고 이 레포를 해당 웹프로젝트의 application과 같은 준위에 vendor라는 등록부에 clone합니다.
우와 같은 조작을 완료하면 namespace기능을 리용할수 있습니다.

추가로 Image Crop기능도 지원합니다.
vendor/gumlet가 그 library입니다.