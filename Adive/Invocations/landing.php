<?php
/**
 * LANDING Agency Invocation.
 * (c) Ferdinand Martin <https://github.com/ferdinandmartin>
 *
 * LANDING Agency Procedure (Bootstrap)
 * This invocation generates:
 * 1 - Frontend with sections
 * 3 - Contact form
 */

/**
 * Invocation Status Array
 */
$invStatus = array();

/**
 * Datetime Set
 */
$dateTime = date('Y-m-d H:i:s');

/**
 * STEP 1: Update Database
 */
$adiveUpdate = $db->prepare("
CREATE TABLE `sections_sec` (
  `id` int(6) NOT NULL,
  `name` varchar(200) DEFAULT NULL COMMENT 'Section Name',
  `content` text COMMENT 'Section Content',
  `url` varchar(200) DEFAULT NULL COMMENT 'Section URL',
  `seo_title` varchar(200) DEFAULT NULL COMMENT 'Head SEO Title',
  `seo_description` tinytext COMMENT 'Head SEO Description',
  `seo_h1` varchar(200) DEFAULT NULL COMMENT 'Body H1 Tag',
  `seo_h2` varchar(200) DEFAULT NULL COMMENT 'Body H2 Tag',
  `creation_date` datetime DEFAULT NULL COMMENT 'Creation Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Web Sections';

ALTER TABLE `sections_sec`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sections_sec`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
  
INSERT INTO `adive_tables` (`id`, `name`, `description`, `win_name`, `win_description`, `table_name_field`, `creationDate`) VALUES
(765, 'sections_sec', 'Web Sections', 'Sections', 'Web Sections', 'name', '".$dateTime."');
  
INSERT INTO `adive_fields` (`table_id_fk`, `name`, `comment`, `win_name`, `win_description`, `win_order`, `win_type`, `win_code`, `creationDate`, `author`) VALUES
(765, 'name', 'Section Name', 'Name', 'Section Name', 10, 1, '', '".$dateTime."', 'admin'),
(765, 'content', 'Section Content', 'Content', 'Section Content (HTML)', 20, 3, '', '".$dateTime."', 'admin'),
(765, 'url', 'Section URL', 'Permalink', 'Section URL Permalink', 30, 1, '', '".$dateTime."', 'admin'),
(765, 'seo_title', 'Head SEO Title', 'SEO Title', 'SEO Title in Head', 50, 1, '', '".$dateTime."', 'admin'),
(765, 'seo_description', 'Head SEO Description', 'SEO Description', 'SEO-META Description in Head', 60, 2, '', '".$dateTime."', 'admin'),
(765, 'seo_h1', 'Body H1 Tag', 'SEO H1', 'Body H1 Tag, must be different of Title.', 70, 1, '', '".$dateTime."', 'admin'),
(765, 'seo_h2', 'Body H2 Tag', 'SEO H2', 'Body H2 Tag, optional.', 80, 1, '', '".$dateTime."', 'admin'),
(765, 'creation_date', 'Creation Date', 'Creation Date', 'Creation Date', 90, 7, '', '".$dateTime."', 'admin');

INSERT INTO `adive_users` (`username`, `password`, `name`, `permissions`, `creationDate`, `activeDate`, `invokeType`, `lastInvoke`) VALUES
('web', '".md5('web')."', 'Web Editor', 1, '".date('Y-m-d')." 00:00:00', '0000-00-00 00:00:00', 'web', '0000-00-00 00:00:00');

INSERT INTO `sections_sec` (`id`, `name`, `content`, `url`, `seo_title`, `seo_description`, `seo_h1`, `seo_h2`, `creation_date`) VALUES
(1, 'Company', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'company', 'SEO Title Inlay', 'SEO Description for this test section.', 'My Company', 'Lorem ipsum dolor sit amet.', '".date('Y-m-d')." 00:00:00');");
$adiveUpdate->execute();
$updateResult = $adiveUpdate->fetchAll(PDO::FETCH_ASSOC);
// Creation Status
($updateResult)? $tablesNot = "Ok" : $tablesNot = "Error: ".$adiveUpdate->errorInfo()[2];
$invStatus["Creating necessary tables and users."] = $tablesNot;
        
/**
 * STEP 2: Create necessary files
 * 
 * CSS Library needed
 */
$cssLib1 = <<<'EOD'
body{overflow-x:hidden;font-family:"Roboto Slab","Helvetica Neue",Helvetica,Arial,sans-serif}.text-muted{color:#777}.text-primary{color:#fed136}p{font-size:14px;line-height:1.75}p.large{font-size:16px}a,a:hover,a:focus,a:active,a.active{outline:0}a{color:#fed136}a:hover,a:focus,a:active,a.active{color:#fec503}h1,h2,h3,h4,h5,h6{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700}.img-centered{margin:0 auto}.bg-light-gray{background-color:#f7f7f7}.bg-darkest-gray{background-color:#222}.btn-primary{color:#fff;background-color:#fed136;border-color:#fed136;font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700}.btn-primary:hover,.btn-primary:focus,.btn-primary:active,.btn-primary.active,.open .dropdown-toggle.btn-primary{color:#fff;background-color:#fec503;border-color:#f6bf01}.btn-primary:active,.btn-primary.active,.open .dropdown-toggle.btn-primary{background-image:none}.btn-primary.disabled,.btn-primary[disabled],fieldset[disabled] .btn-primary,.btn-primary.disabled:hover,.btn-primary[disabled]:hover,fieldset[disabled] .btn-primary:hover,.btn-primary.disabled:focus,.btn-primary[disabled]:focus,fieldset[disabled] .btn-primary:focus,.btn-primary.disabled:active,.btn-primary[disabled]:active,fieldset[disabled] .btn-primary:active,.btn-primary.disabled.active,.btn-primary[disabled].active,fieldset[disabled] .btn-primary.active{background-color:#fed136;border-color:#fed136}.btn-primary .badge{color:#fed136;background-color:#fff}.btn-xl{color:#fff;background-color:#fed136;border-color:#fed136;font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700;border-radius:3px;font-size:18px;padding:20px 40px}.btn-xl:hover,.btn-xl:focus,.btn-xl:active,.btn-xl.active,.open .dropdown-toggle.btn-xl{color:#fff;background-color:#fec503;border-color:#f6bf01}.btn-xl:active,.btn-xl.active,.open .dropdown-toggle.btn-xl{background-image:none}.btn-xl.disabled,.btn-xl[disabled],fieldset[disabled] .btn-xl,.btn-xl.disabled:hover,.btn-xl[disabled]:hover,fieldset[disabled] .btn-xl:hover,.btn-xl.disabled:focus,.btn-xl[disabled]:focus,fieldset[disabled] .btn-xl:focus,.btn-xl.disabled:active,.btn-xl[disabled]:active,fieldset[disabled] .btn-xl:active,.btn-xl.disabled.active,.btn-xl[disabled].active,fieldset[disabled] .btn-xl.active{background-color:#fed136;border-color:#fed136}.btn-xl .badge{color:#fed136;background-color:#fff}.navbar-default{background-color:#222;border-color:transparent}.navbar-default .navbar-brand{color:#fed136;font-family:"Kaushan Script","Helvetica Neue",Helvetica,Arial,cursive}.navbar-default .navbar-brand:hover,.navbar-default .navbar-brand:focus,.navbar-default .navbar-brand:active,.navbar-default .navbar-brand.active{color:#fec503}.navbar-default .navbar-collapse{border-color:rgba(255,255,255,.02)}.navbar-default .navbar-toggle{background-color:#fed136;border-color:#fed136}.navbar-default .navbar-toggle .icon-bar{background-color:#fff}.navbar-default .navbar-toggle:hover,.navbar-default .navbar-toggle:focus{background-color:#fed136}.navbar-default .nav li a{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:400;letter-spacing:1px;color:#fff}.navbar-default .nav li a:hover,.navbar-default .nav li a:focus{color:#fed136;outline:0}.navbar-default .navbar-nav>.active>a{border-radius:0;color:#fff;background-color:#fed136}.navbar-default .navbar-nav>.active>a:hover,.navbar-default .navbar-nav>.active>a:focus{color:#fff;background-color:#fec503}@media (min-width:768px){.navbar-default{background-color:transparent;padding:25px 0;-webkit-transition:padding .3s;-moz-transition:padding .3s;transition:padding .3s;border:0}.navbar-default .navbar-brand{font-size:2em;-webkit-transition:all .3s;-moz-transition:all .3s;transition:all .3s}.navbar-default .navbar-nav>.active>a{border-radius:3px}.navbar-default.navbar-shrink{background-color:#222;padding:10px 0}.navbar-default.navbar-shrink .navbar-brand{font-size:1.5em}}header{background-image:url(https://static.pexels.com/photos/103875/pexels-photo-103875.jpeg);background-repeat:none;background-attachment:scroll;background-position:center center;-webkit-background-size:cover;-moz-background-size:cover;background-size:cover;-o-background-size:cover;text-align:center;color:#fff}header .intro-text{padding-top:100px;padding-bottom:50px}header .intro-text .intro-lead-in{font-family:"Droid Serif","Helvetica Neue",Helvetica,Arial,sans-serif;font-style:italic;font-size:22px;line-height:22px;margin-bottom:25px}header .intro-text .intro-heading{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700;font-size:50px;line-height:50px;margin-bottom:25px}@media (min-width:768px){header .intro-text{padding-top:300px;padding-bottom:200px}header .intro-text .intro-lead-in{font-family:"Droid Serif","Helvetica Neue",Helvetica,Arial,sans-serif;font-style:italic;font-size:40px;line-height:40px;margin-bottom:25px}header .intro-text .intro-heading{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700;font-size:75px;line-height:75px;margin-bottom:50px}}section{padding:100px 0}section h2.section-heading{font-size:40px;margin-top:0;margin-bottom:15px}section h3.section-subheading{font-size:16px;font-family:"Droid Serif","Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:none;font-style:italic;font-weight:400;margin-bottom:75px}@media (min-width:768px){section{padding:150px 0}}.service-heading{margin:15px 0;text-transform:none}#portfolio .portfolio-item{margin:0 0 15px;right:0}#portfolio .portfolio-item .portfolio-link{display:block;position:relative;max-width:400px;margin:0 auto}#portfolio .portfolio-item .portfolio-link .portfolio-hover{background:rgba(254,209,54,.9);position:absolute;width:100%;height:100%;opacity:0;transition:all ease .5s;-webkit-transition:all ease .5s;-moz-transition:all ease .5s}#portfolio .portfolio-item .portfolio-link .portfolio-hover:hover{opacity:1}#portfolio .portfolio-item .portfolio-link .portfolio-hover .portfolio-hover-content{position:absolute;width:100%;height:20px;font-size:20px;text-align:center;top:50%;margin-top:-12px;color:#fff}#portfolio .portfolio-item .portfolio-link .portfolio-hover .portfolio-hover-content i{margin-top:-12px}#portfolio .portfolio-item .portfolio-link .portfolio-hover .portfolio-hover-content h3,#portfolio .portfolio-item .portfolio-link .portfolio-hover .portfolio-hover-content h4{margin:0}#portfolio .portfolio-item .portfolio-caption{max-width:400px;margin:0 auto;background-color:#fff;text-align:center;padding:25px}#portfolio .portfolio-item .portfolio-caption h4{text-transform:none;margin:0}#portfolio .portfolio-item .portfolio-caption p{font-family:"Droid Serif","Helvetica Neue",Helvetica,Arial,sans-serif;font-style:italic;font-size:16px;margin:0}#portfolio *{z-index:2}@media (min-width:767px){#portfolio .portfolio-item{margin:0 0 30px}}.timeline{list-style:none;padding:0;position:relative}.timeline:before{top:0;bottom:0;position:absolute;content:"";width:2px;background-color:#f1f1f1;left:40px;margin-left:-1.5px}.timeline>li{margin-bottom:50px;position:relative;min-height:50px}.timeline>li:before,.timeline>li:after{content:" ";display:table}.timeline>li:after{clear:both}.timeline>li .timeline-panel{width:100%;float:right;padding:0 20px 0 100px;position:relative;text-align:left}.timeline>li .timeline-panel:before{border-left-width:0;border-right-width:15px;left:-15px;right:auto}.timeline>li .timeline-panel:after{border-left-width:0;border-right-width:14px;left:-14px;right:auto}.timeline>li .timeline-image{left:0;margin-left:0;width:80px;height:80px;position:absolute;z-index:100;background-color:#fed136;color:#fff;border-radius:100%;border:7px solid #f1f1f1;text-align:center}.timeline>li .timeline-image h4{font-size:10px;margin-top:12px;line-height:14px}.timeline>li.timeline-inverted>.timeline-panel{float:right;text-align:left;padding:0 20px 0 100px}.timeline>li.timeline-inverted>.timeline-panel:before{border-left-width:0;border-right-width:15px;left:-15px;right:auto}.timeline>li.timeline-inverted>.timeline-panel:after{border-left-width:0;border-right-width:14px;left:-14px;right:auto}.timeline>li:last-child{margin-bottom:0}.timeline .timeline-heading h4{margin-top:0;color:inherit}.timeline .timeline-heading h4.subheading{text-transform:none}.timeline .timeline-body>p,.timeline .timeline-body>ul{margin-bottom:0}@media (min-width:768px){.timeline:before{left:50%}.timeline>li{margin-bottom:100px;min-height:100px}.timeline>li .timeline-panel{width:41%;float:left;padding:0 20px 20px 30px;text-align:right}.timeline>li .timeline-image{width:100px;height:100px;left:50%;margin-left:-50px}.timeline>li .timeline-image h4{font-size:13px;margin-top:16px;line-height:18px}.timeline>li.timeline-inverted>.timeline-panel{float:right;text-align:left;padding:0 30px 20px 20px}}@media (min-width:992px){.timeline>li{min-height:150px}.timeline>li .timeline-panel{padding:0 20px 20px}.timeline>li .timeline-image{width:150px;height:150px;margin-left:-75px}.timeline>li .timeline-image h4{font-size:18px;margin-top:30px;line-height:26px}.timeline>li.timeline-inverted>.timeline-panel{padding:0 20px 20px}}@media (min-width:1200px){.timeline>li{min-height:170px}.timeline>li .timeline-panel{padding:0 20px 20px 100px}.timeline>li .timeline-image{width:170px;height:170px;margin-left:-85px}.timeline>li .timeline-image h4{margin-top:40px}.timeline>li.timeline-inverted>.timeline-panel{padding:0 100px 20px 20px}}.team-member{text-align:center;margin-bottom:50px}.team-member img{margin:0 auto;border:7px solid #fff}.team-member h4{margin-top:25px;margin-bottom:0;text-transform:none}.team-member p{margin-top:0}aside.clients img{margin:50px auto}section#contact{background-color:#222;background-image:url(../img/map-image.png);background-position:center;background-repeat:no-repeat}section#contact .section-heading{color:#fff}section#contact .form-group{margin-bottom:25px}section#contact .form-group input,section#contact .form-group textarea{padding:20px}section#contact .form-group input.form-control{height:auto}section#contact .form-group textarea.form-control{height:236px}section#contact .form-control:focus{border-color:#fed136;box-shadow:none}section#contact ::-webkit-input-placeholder{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700;color:#bbb}section#contact :-moz-placeholder{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700;color:#bbb}section#contact ::-moz-placeholder{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700;color:#bbb}section#contact :-ms-input-placeholder{font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;font-weight:700;color:#bbb}section#contact .text-danger{color:#e74c3c}footer{padding:25px 0;text-align:center}footer span.copyright{line-height:40px;font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;text-transform:none}footer ul.quicklinks{margin-bottom:0;line-height:40px;font-family:Montserrat,"Helvetica Neue",Helvetica,Arial,sans-serif;text-transform:uppercase;text-transform:none}ul.social-buttons{margin-bottom:0}ul.social-buttons li a{display:block;background-color:#222;height:40px;width:40px;border-radius:100%;font-size:20px;line-height:40px;color:#fff;outline:0;-webkit-transition:all .3s;-moz-transition:all .3s;transition:all .3s}ul.social-buttons li a:hover,ul.social-buttons li a:focus,ul.social-buttons li a:active{background-color:#fed136}.btn:focus,.btn:active,.btn.active,.btn:active:focus{outline:0}.portfolio-modal .modal-content{border-radius:0;background-clip:border-box;-webkit-box-shadow:none;box-shadow:none;border:0;min-height:100%;padding:100px 0;text-align:center}.portfolio-modal .modal-content h2{margin-bottom:15px;font-size:3em}.portfolio-modal .modal-content p{margin-bottom:30px}.portfolio-modal .modal-content p.item-intro{margin:20px 0 30px;font-family:"Droid Serif","Helvetica Neue",Helvetica,Arial,sans-serif;font-style:italic;font-size:16px}.portfolio-modal .modal-content ul.list-inline{margin-bottom:30px;margin-top:0}.portfolio-modal .modal-content img{margin-bottom:30px}.portfolio-modal .close-modal{position:absolute;width:75px;height:75px;background-color:transparent;top:25px;right:25px;cursor:pointer}.portfolio-modal .close-modal:hover{opacity:.3}.portfolio-modal .close-modal .lr{height:75px;width:1px;margin-left:35px;background-color:#222;transform:rotate(45deg);-ms-transform:rotate(45deg);-webkit-transform:rotate(45deg);z-index:1051}.portfolio-modal .close-modal .lr .rl{height:75px;width:1px;background-color:#222;transform:rotate(90deg);-ms-transform:rotate(90deg);-webkit-transform:rotate(90deg);z-index:1052}.portfolio-modal .modal-backdrop{opacity:0;display:none}::-moz-selection{text-shadow:none;background:#fed136}::selection{text-shadow:none;background:#fed136}img::selection{background:0 0}img::-moz-selection{background:0 0}body{webkit-tap-highlight-color:#fed136}
EOD;

$filename = 'Views/css/agency.css';
$dirname = dirname($filename);
if (!is_dir($dirname))
{
    mkdir($dirname, 0755, true);
}
$cssFile = fopen($filename, 'w');
fwrite($cssFile, $cssLib1);
fclose($cssFile);
// Creation Status
(file_exists('Views/css/agency.css'))? $cssNot = "Ok" : $cssNot = "Error: File not exists.";
$invStatus["Creating CSS files."] = $cssNot;

/**
 * JS Library needed
 */
$jsLib1 = <<<'EOD'
/*!
 * Start Bootstrap - Agency Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
})

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});
EOD;

$filename = 'Views/js/agency.js';
$dirname = dirname($filename);
if (!is_dir($dirname))
{
    mkdir($dirname, 0755, true);
}
$jsFile1 = fopen($filename, 'w');
fwrite($jsFile1, $jsLib1);
fclose($jsFile1);
// Creation Status
(file_exists('Views/js/agency.js'))? $jsNot = "Ok" : $jsNot = "Error: File not exists.";
$invStatus["Creating JS Landing files."] = $jsNot;

/**
 * Contact JS Lib
 */
$jsLib2 = <<<'EOD'
$(function() {

    $("#contactForm input,#contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            var name = $("input#name").val();
            var email = $("input#email").val();
            var phone = $("input#phone").val();
            var message = $("textarea#message").val();
            var firstName = name; // For Success/Failure Message
            // Check for white space in name for Success/Fail message
            if (firstName.indexOf(' ') >= 0) {
                firstName = name.split(' ').slice(0, -1).join(' ');
            }
            $.ajax({
                url: "././mail/contact_me.php",
                type: "POST",
                data: {
                    name: name,
                    phone: phone,
                    email: email,
                    message: message
                },
                cache: false,
                success: function() {
                    // Success message
                    $('#success').html("<div class='alert alert-success'>");
                    $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#success > .alert-success')
                        .append("<strong>Your message has been sent. </strong>");
                    $('#success > .alert-success')
                        .append('</div>');

                    //clear all fields
                    $('#contactForm').trigger("reset");
                },
                error: function() {
                    // Fail message
                    $('#success').html("<div class='alert alert-danger'>");
                    $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#success > .alert-danger').append("<strong>Sorry " + firstName + ", it seems that my mail server is not responding. Please try again later!");
                    $('#success > .alert-danger').append('</div>');
                    //clear all fields
                    $('#contactForm').trigger("reset");
                },
            })
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
EOD;

$jsFile2 = fopen('Views/js/contact_me.js', 'w');
fwrite($jsFile2, $jsLib2);
fclose($jsFile2);
// Creation Status
(file_exists('Views/js/contact_me.js'))? $jsNot = "Ok" : $jsNot = "Error: File not exists.";
$invStatus["Creating JS Contact files."] = $jsNot;

/**
 * Animated Header JS Library needed
 */
$jsLib3 = <<<'EOD'
/**
 * cbpAnimatedHeader.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
var cbpAnimatedHeader = (function() {

	var docElem = document.documentElement,
		header = document.querySelector( '.navbar-default' ),
		didScroll = false,
		changeHeaderOn = 300;

	function init() {
		window.addEventListener( 'scroll', function( event ) {
			if( !didScroll ) {
				didScroll = true;
				setTimeout( scrollPage, 250 );
			}
		}, false );
	}

	function scrollPage() {
		var sy = scrollY();
		if ( sy >= changeHeaderOn ) {
			classie.add( header, 'navbar-shrink' );
		}
		else {
			classie.remove( header, 'navbar-shrink' );
		}
		didScroll = false;
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();

})();
EOD;

$jsFile3 = fopen('Views/js/cbpAnimatedHeader.js', 'w');
fwrite($jsFile3, $jsLib3);
fclose($jsFile3);
// Creation Status
(file_exists('Views/js/cbpAnimatedHeader.js'))? $jsNot = "Ok" : $jsNot = "Error: File not exists.";
$invStatus["Creating JS Landing files."] = $jsNot;

/**
 * Classie JS Library needed
 */
$jsLib4 = <<<'EOD'
/*!
 * classie - class helper functions
 * from bonzo https://github.com/ded/bonzo
 * 
 * classie.has( elem, 'my-class' ) -> true/false
 * classie.add( elem, 'my-new-class' )
 * classie.remove( elem, 'my-unwanted-class' )
 * classie.toggle( elem, 'my-class' )
 */

/*jshint browser: true, strict: true, undef: true */
/*global define: false */

( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}

})( window );
EOD;

$jsFile4 = fopen('Views/js/classie.js', 'w');
fwrite($jsFile4, $jsLib4);
fclose($jsFile4);
// Creation Status
(file_exists('Views/js/classie.js'))? $jsNot = "Ok" : $jsNot = "Error: File not exists.";
$invStatus["Creating JS Landing files."] = $jsNot;

/**
 * WEB Body index.php
 */
$indexView = <<<'EOD'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=$title?> - Agency</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=basePath()?>/Adive/Internal/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=asset('agency.css')?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=basePath()?>/Adive/Internal/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">Start Bootstrap</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Services</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio">Portfolio</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#team">Team</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in">Welcome To Our Studio!</div>
                <div class="intro-heading">It's Nice To Meet You</div>
                <a href="#services" class="page-scroll btn btn-xl">Tell Me More</a>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Services</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-shopping-cart fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">E-Commerce</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Responsive Design</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Web Security</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Grid Section -->
    <section id="portfolio" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Portfolio</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/roundicons.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Round Icons</h4>
                        <p class="text-muted">Graphic Design</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/startup-framework.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Startup Framework</h4>
                        <p class="text-muted">Website Design</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/treehouse.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Treehouse</h4>
                        <p class="text-muted">Website Design</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal4" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/golden.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Golden</h4>
                        <p class="text-muted">Website Design</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal5" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/escape.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Escape</h4>
                        <p class="text-muted">Website Design</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal6" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/dreams.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Dreams</h4>
                        <p class="text-muted">Website Design</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">About</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="timeline">
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="https://static.pexels.com/photos/25037/pexels-photo.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2009-2011</h4>
                                    <h4 class="subheading">Our Humble Beginnings</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="https://static.pexels.com/photos/31323/pexels-photo.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>March 2011</h4>
                                    <h4 class="subheading">An Agency is Born</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="https://static.pexels.com/photos/54257/pexels-photo-54257.jpeg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>December 2012</h4>
                                    <h4 class="subheading">Transition to Full Service</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="img/about/4.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>July 2014</h4>
                                    <h4 class="subheading">Phase Two Expansion</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <h4>Be Part
                                    <br>Of Our
                                    <br>Story!</h4>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Our Amazing Team</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="https://static.pexels.com/photos/7369/startup-photos.jpg" class="img-responsive img-circle" alt="">
                        <h4>Kay Garland</h4>
                        <p class="text-muted">Lead Designer</p>
                        <ul class="list-inline social-buttons">
                            <li><a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="https://static.pexels.com/photos/7370/startup-photo.jpg" class="img-responsive img-circle" alt="">
                        <h4>Larry Parker</h4>
                        <p class="text-muted">Lead Marketer</p>
                        <ul class="list-inline social-buttons">
                            <li><a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="https://static.pexels.com/photos/7366/startup-photos.jpg" class="img-responsive img-circle" alt="">
                        <h4>Diana Pertersen</h4>
                        <p class="text-muted">Lead Developer</p>
                        <ul class="list-inline social-buttons">
                            <li><a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <p class="large text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Clients Aside -->
    <!--<aside class="clients">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/envato.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/designmodo.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/themeforest.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/creative-market.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
            </div>
        </div>
    </aside>-->
    
    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contact Us</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Your Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Your Phone *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Your Message *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; Your Website 2014</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="#">Privacy Policy</a>
                        </li>
                        <li><a href="#">Terms of Use</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Portfolio Modals -->
    <!-- Use the modals below to showcase details about your portfolio projects! -->

    <!-- Portfolio Modal 1 -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/roundicons-free.png" alt="">
                            <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                            <p>
                                <strong>Want these icons in this portfolio item sample?</strong>You can download 60 of them for free, courtesy of <a href="https://getdpd.com/cart/hoplink/18076?referrer=bvbo4kax5k8ogc">RoundIcons.com</a>, or you can purchase the 1500 icon set <a href="https://getdpd.com/cart/hoplink/18076?referrer=bvbo4kax5k8ogc">here</a>.</p>
                            <ul class="list-inline">
                                <li>Date: July 2014</li>
                                <li>Client: Round Icons</li>
                                <li>Category: Graphic Design</li>
                            </ul>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 2 -->
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Heading</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/startup-framework-preview.png" alt="">
                            <p><a href="http://designmodo.com/startup/?u=787">Startup Framework</a> is a website builder for professionals. Startup Framework contains components and complex blocks (PSD+HTML Bootstrap themes and templates) which can easily be integrated into almost any design. All of these components are made in the same style, and can easily be integrated into projects, allowing you to create hundreds of solutions for your future projects.</p>
                            <p>You can preview Startup Framework <a href="http://designmodo.com/startup/?u=787">here</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 3 -->
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/treehouse-preview.png" alt="">
                            <p>Treehouse is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. This is bright and spacious design perfect for people or startup companies looking to showcase their apps or other projects.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/treehouse-free-psd-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 4 -->
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/golden-preview.png" alt="">
                            <p>Start Bootstrap's Agency theme is based on Golden, a free PSD website template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Golden is a modern and clean one page web template that was made exclusively for Best PSD Freebies. This template has a great portfolio, timeline, and meet your team sections that can be easily modified to fit your needs.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/golden-free-one-page-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 5 -->
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/escape-preview.png" alt="">
                            <p>Escape is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Escape is a one page web template that was designed with agencies in mind. This template is ideal for those looking for a simple one page solution to describe your business and offer your services.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/escape-one-page-psd-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 6 -->
    <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/dreams-preview.png" alt="">
                            <p>Dreams is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Dreams is a modern one page web template designed for almost any purpose. It’s a beautiful template that’s designed with the Bootstrap framework in mind.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/dreams-free-one-page-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?=iasset('jquery.min.js')?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=iasset('bootstrap.min.js')?>"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="<?=asset('classie.js')?>"></script>
    <script src="<?=asset('cbpAnimatedHeader.js')?>"></script>

    <!-- Contact Form JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqBootstrapValidation/1.3.7/jqBootstrapValidation.min.js"></script>
    <script src="<?=asset('contact_me.js')?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=asset('agency.js')?>"></script>

</body>
</html>
EOD;
$indexFile = fopen('Views/index.php', 'w');
fwrite($indexFile, $indexView);
fclose($indexFile);
// Creation Status
(file_exists('Views/index.php'))? $indexNot = "Ok" : $indexNot = "Error: File not exists.";
$invStatus["Creating Index page."] = $indexNot;

/**
 * STEP 3: Update Controller
 *
 * Controller Append to Default Controller
 */
$controllerView = <<<'EOD'
<?php
/*
 * LANDING Controller Invocation.
 * (c) Ferdinand Martin <info@adive.es>
 */

/*
 * ************** CAUTION **************
 *
 * THIS FILE WILL BE OVERWRITTEN ON CONTROLLER API INVOCATION
 *
 * ************** CAUTION **************
 */
 
/**
 * @Route(GET) Landing Home Page.
 */
$API->get('/', 
    function() use($API, $db) {
        pathActive('/');
        $postsQ = $db->prepare("SELECT * FROM posts_pos ORDER BY creation_date DESC");
        $postsQ->execute();
        $posts = $postsQ->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Default:', array(
            'posts' => $posts,
            'title' => 'Clean Blog',
            'seo_title' => 'Clean Blog',
            'seo_description' => 'A Clean Blog Theme by Start Bootstrap',
        ));
    }
)->name('home');
EOD;

$controllerFile = fopen('Controller/Default.php', 'w');
fwrite($controllerFile, $controllerView);
fclose($controllerFile);
// Creation Status
(file_exists('Controller/Default.php'))? $contNot = "Ok" : $contNot = "Error: File not exists.";
$invStatus["Updating Default Controller."] = $contNot;
