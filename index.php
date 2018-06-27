<?php include "autoload.php";?>
<!DOCTYPE html>
<html >
<head>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-82194269-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-82194269-2');
    </script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-7420807032750890",
            enable_page_level_ads: true
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">

    <meta name="Description" content="<?php echo $_ENV["site"]["description"]?>">
    <meta name="distribution" content="Global">
    <meta name="Robots" content="INDEX,FOLLOW">



    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular-route.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-translate/2.18.1/angular-translate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-translate/2.18.1/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-sanitize.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-animate.js"></script>

    <script>


        var app = angular.module("app", ['ngSanitize',"ngRoute",'pascalprecht.translate','ngAnimate']);

        app.config(function($routeProvider) {
            $routeProvider
                .when("/", {
                    templateUrl : "views/home.html",
                    controller:"home-controller"
                })
                .when("/sent-messages", {
                    templateUrl : "views/sent-messages.html",
                    controller:"home-controller"
                })
                .when("/contact-me", {
                    templateUrl : "views/contact-me.html",
                    controller:"contact-me-controller"
                })


        });
        app.config(['$translateProvider', function ($translateProvider, $translatePartialLoaderProvider) {
            $translateProvider.useStaticFilesLoader({
                files: [
                    {
                        prefix: 'lang/',
                        suffix: '.json'
                    }]
            });
            $translateProvider.preferredLanguage('en');


        }]);

        var max_messages = 150;


/*        function focusIn() {

                document.body.classList.add("in-focus");

        }
        function focusOut() {
            document.body.classList.remove("in-focus");
        }*/

function bindFocusInOut() {
    var fields = document.querySelectorAll("input,textarea");
    fields.forEach(function (t) {
        t.addEventListener('focusin', function () {
            document.body.classList.add("in-focus");
        });

        t.addEventListener('focusout', function () {
            document.body.classList.remove("in-focus");
        });



    })
}
        app.controller('home-controller', function($scope,$window,$translate) {

            bindFocusInOut();

            $scope.message = {};

            $translate.use('<?php echo $_ENV["lang"]; ?>');


            /*
            $scope.focusIn=function () {

                document.body.classList.add("in-focus");

            }
            $scope.focusOut=function () {

              document.body.classList.remove("in-focus");
              return true;
            }*/


            $scope.loadMessages=function () {
                var messages= ($window.localStorage.getItem("whagen_messages"))?JSON.parse($window.localStorage.getItem("whagen_messages")):[];

                return messages;
            };

            $scope.saveMessage=function (message) {


                $scope.sentMessages.push(message);

                if($scope.sentMessages.length > max_messages)
                {
                    $scope.sentMessages=   $scope.sentMessages.slice(1,$scope.sentMessages.length);
                }


                $window.localStorage.setItem("whagen_messages",JSON.stringify($scope.sentMessages));

            }

            $scope.sentMessages = $scope.loadMessages();


            $scope.deleteMessage=function (message) {

               $scope.sentMessages.splice($scope.sentMessages.indexOf(message),1);


                $window.localStorage.setItem("whagen_messages",JSON.stringify($scope.sentMessages));
            }

            $scope.window = false;
            $scope.generateMessage=function () {

                $scope.message.text = (!$scope.message.text)?"":$scope.message.text;
                $scope.message.time = new Date();
                var apiUrl = 'https://api.whatsapp.com/send';


                if($window.innerWidth > 800){
                    apiUrl = 'https://web.whatsapp.com/send';
                }

                var url = apiUrl+"?phone="+$scope.message.phone+"&text="+encodeURIComponent($scope.message.text);

                $scope.saveMessage(angular.copy($scope.message));
                if($scope.window)
                {
                    $scope.window.close();
                }

                $scope.window = $window.open(url);

            }

        });


        app.controller('contact-me-controller',function ($scope,$http) {


            bindFocusInOut();

            $scope.sendContact=function () {

                var contact= angular.copy($scope.contact);
                var data  = Object.keys(contact).map(function(k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(contact[k])
                }).join('&');

                $http.post('contact-me.php',data,{
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                    }
                })
                    .then(function (response) {
                        if(response.data == "false")
                        {
                            alert($scope.$eval("'Error al enviar el formulario de contacto. Intentalo más tarde.' | translate"));
                        }
                        else
                        {
                            $scope.formSent =true;
                        }


                    },function (response) {
                        console.log(response);
                    })

            }

        })






    </script>

    <title>Whagen</title>


</head>
<body data-ng-app="app">

<aside>
    <div class="banner-c"></div>
</aside>
<aside class="banner-c-right">
    <div class="banner-c"></div>
</aside>

<nav class="float-left">
    <a class="logo">
    </a>
    <a class="logo-title">Whagen</a>
    <a class="logo-slogan">
        <p>{{'Enviá Whatsapps sin agendar contactos' | translate}}</p>
    </a>
</nav>

<!--
<div class="banner-b"></div>
-->
<main  data-ng-view class="fade">



</main>



<!--
<div class="banner-a"></div>
-->

</body>
</html>