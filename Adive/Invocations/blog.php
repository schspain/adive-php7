<?php
/**
 * Blog Clean Invocation.
 * (c) Ferdinand Martin <https://github.com/ferdinandmartin>
 *
 * A Clean Blog Theme by Start Bootstrap (Bootstrap)
 * This invocation generates:
 * 1 - Frontend with posts
 * 3 - About page
 * 2 - Backoffice
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
CREATE TABLE `posts_pos` (
  `id` int(6) NOT NULL,
  `title` varchar(200) DEFAULT NULL COMMENT 'Post title',
  `subtitle` varchar(200) DEFAULT NULL COMMENT 'Inlay post',
  `content` text COMMENT 'Content',
  `url` varchar(200) DEFAULT NULL COMMENT 'Post URL',
  `image` varchar(200) DEFAULT NULL COMMENT 'Post Image',
  `seo_title` varchar(200) DEFAULT NULL COMMENT 'Head SEO Title',
  `seo_description` tinytext COMMENT 'Head SEO Description',
  `seo_h1` varchar(200) DEFAULT NULL COMMENT 'Body H1 Tag',
  `seo_h2` varchar(200) DEFAULT NULL COMMENT 'Body H2 Tag',
  `creation_date` datetime DEFAULT NULL COMMENT 'Creation Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Web Sections';

ALTER TABLE `posts_pos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts_pos`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
  
INSERT INTO `adive_tables` (`id`, `name`, `description`, `win_name`, `win_description`, `table_name_field`, `creationDate`) VALUES
(766, 'posts_pos', 'Blog Posts', 'Posts', 'Blog Posts', 'title', '".$dateTime."');
  
INSERT INTO `adive_fields` (`table_id_fk`, `name`, `comment`, `win_name`, `win_description`, `win_order`, `win_type`, `win_code`, `creationDate`, `author`, `visible`) VALUES
(766, 'title', 'Post title', 'Post title', 'Section Name', 10, 1, '', '".$dateTime."', 'blog', 1),
(766, 'subtitle', 'Post resume', 'Inlay Post', 'Section Name', 15, 1, '', '".$dateTime."', 'blog', 1),
(766, 'content', 'Content', 'Content', 'Content', 20, 3, '', '".$dateTime."', 'blog', 1),
(766, 'url', 'Section URL', 'Permalink', 'Post URL Permalink', 30, 1, '', '".$dateTime."', 'blog', 1),
(766, 'image', 'Featured image', 'Image', 'Featured image', 40, 1, '', '".$dateTime."', 'blog', 1),
(766, 'seo_title', 'Head SEO Title', 'SEO Title', 'SEO Title in Head', 50, 1, '', '".$dateTime."', 'blog', 1),
(766, 'seo_description', 'Head SEO Description', 'SEO Description', 'SEO-META Description in Head', 60, 2, '', '".$dateTime."', 'blog', 1),
(766, 'seo_h1', 'Body H1 Tag', 'SEO H1', 'Body H1 Tag, must be different of Title.', 70, 1, '', '".$dateTime."', 'blog', 1),
(766, 'seo_h2', 'Body H2 Tag', 'SEO H2', 'Body H2 Tag, optional.', 80, 1, '', '".$dateTime."', 'blog', 1),
(766, 'creation_date', 'Creation Date', 'Creation Date', 'Creation Date', 90, 7, '', '".$dateTime."', 'blog', 1);

INSERT INTO `adive_users` (`username`, `password`, `name`, `permissions`, `creationDate`, `activeDate`, `invokeType`, `lastInvoke`) VALUES
('blog', '".md5('blog')."', 'Clean Blog', 3, '".date('Y-m-d')." 00:00:00', '".date('Y-m-d')." 00:00:00', 'web', '".date('Y-m-d')." 00:00:00');

INSERT INTO `posts_pos` (`id`, `title`, `subtitle`, `content`, `url`, `image`, `seo_title`, `seo_description`, `seo_h1`, `seo_h2`, `creation_date`) VALUES
(1, 'Man must explore, and this is exploration at its greatest', 'Problems look mighty small from 150 miles up', '<p>Never in all their history have men been able truly to conceive of the world as one: a single sphere, a globe, having the qualities of a globe, a round earth in which all the directions eventually meet, in which there is no center because every point, or none, is center — an equal earth which all men occupy as equals. The airman\'s earth, if free men make it, will be truly round: a globe in practice, not in theory.</p>

                    <p>Science cuts two ways, of course; its products can be used for both good and evil. But there\'s no turning back from science. The early warnings about technological dangers also come from science.</p>

                    <p>What was most significant about the lunar voyage was not that man set foot on the Moon but that they set eye on the earth.</p>

                    <p>A Chinese tale tells of some men sent to harm a young girl who, upon seeing her beauty, become her protectors rather than her violators. That\'s how I felt seeing the Earth for the first time. I could not help but love and cherish her.</p>

                    <p>For those who have seen the Earth from space, and for the hundreds and perhaps thousands more who will, the experience most certainly changes your perspective. The things that we share in our world are far more valuable than those which divide us.</p>', 'man-must-explore', 'https://static.pexels.com/photos/2156/sky-earth-space-working.jpg', 'The Final Frontier', 'The dreams of yesterday are the hopes of today and the reality of tomorrow. Science has not yet mastered prophecy. We predict too much for the next year and yet far too little for the next ten.', 'Man must explore, and this is exploration at its greatest', 'Problems look mighty small from 150 miles up', '".date('Y-m-d H:i:s')."');");
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
/*!
 * Clean Blog v1.0.0 (http://startbootstrap.com)
 * Copyright 2014 Start Bootstrap
 * Licensed under Apache 2.0 (https://github.com/IronSummitMedia/startbootstrap/blob/gh-pages/LICENSE)
 */

body{font-family:Lora,'Times New Roman',serif;font-size:20px;color:#404040}p{line-height:1.5;margin:30px 0}p a{text-decoration:underline}h1,h2,h3,h4,h5,h6{font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:800}a{color:#404040}a:hover,a:focus{color:#0085a1}a img:hover,a img:focus{cursor:zoom-in}blockquote{color:gray;font-style:italic}hr.small{max-width:100px;margin:15px auto;border-width:4px;border-color:#fff}.navbar-custom{position:absolute;top:0;left:0;width:100%;z-index:3;font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif}.navbar-custom .navbar-brand{font-weight:800}.navbar-custom .nav li a{text-transform:uppercase;font-size:12px;font-weight:800;letter-spacing:1px}@media only screen and (min-width:768px){.navbar-custom{background:0 0;border-bottom:1px solid transparent}.navbar-custom .navbar-brand{color:#fff;padding:20px}.navbar-custom .navbar-brand:hover,.navbar-custom .navbar-brand:focus{color:rgba(255,255,255,.8)}.navbar-custom .nav li a{color:#fff;padding:20px}.navbar-custom .nav li a:hover,.navbar-custom .nav li a:focus{color:rgba(255,255,255,.8)}}@media only screen and (min-width:1170px){.navbar-custom{-webkit-transition:background-color .3s;-moz-transition:background-color .3s;transition:background-color .3s;-webkit-transform:translate3d(0,0,0);-moz-transform:translate3d(0,0,0);-ms-transform:translate3d(0,0,0);-o-transform:translate3d(0,0,0);transform:translate3d(0,0,0);-webkit-backface-visibility:hidden;backface-visibility:hidden}.navbar-custom.is-fixed{position:fixed;top:-61px;background-color:rgba(255,255,255,.9);border-bottom:1px solid #f2f2f2;-webkit-transition:-webkit-transform .3s;-moz-transition:-moz-transform .3s;transition:transform .3s}.navbar-custom.is-fixed .navbar-brand{color:#404040}.navbar-custom.is-fixed .navbar-brand:hover,.navbar-custom.is-fixed .navbar-brand:focus{color:#0085a1}.navbar-custom.is-fixed .nav li a{color:#404040}.navbar-custom.is-fixed .nav li a:hover,.navbar-custom.is-fixed .nav li a:focus{color:#0085a1}.navbar-custom.is-visible{-webkit-transform:translate3d(0,100%,0);-moz-transform:translate3d(0,100%,0);-ms-transform:translate3d(0,100%,0);-o-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0)}}.intro-header{background-color:gray;background:no-repeat center center;background-attachment:scroll;-webkit-background-size:cover;-moz-background-size:cover;background-size:cover;-o-background-size:cover;margin-bottom:50px}.intro-header .site-heading,.intro-header .post-heading,.intro-header .page-heading{padding:100px 0 50px;color:#fff}@media only screen and (min-width:768px){.intro-header .site-heading,.intro-header .post-heading,.intro-header .page-heading{padding:150px 0}}.intro-header .site-heading,.intro-header .page-heading{text-align:center}.intro-header .site-heading h1,.intro-header .page-heading h1{margin-top:0;font-size:50px;color:#FFF}.intro-header .site-heading .subheading,.intro-header .page-heading .subheading{font-size:24px;line-height:1.1;display:block;font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:300;margin:10px 0 0}@media only screen and (min-width:768px){.intro-header .site-heading h1,.intro-header .page-heading h1{font-size:80px;color:#FFF;}}.intro-header .post-heading h1{font-size:35px;color:#FFF;}.intro-header .post-heading .subheading,.intro-header .post-heading .meta{line-height:1.1;display:block}.intro-header .post-heading .subheading{font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:24px;margin:10px 0 30px;font-weight:600}.intro-header .post-heading .meta{font-family:Lora,'Times New Roman',serif;font-style:italic;font-weight:300;font-size:20px}.intro-header .post-heading .meta a{color:#fff}@media only screen and (min-width:768px){.intro-header .post-heading h1{font-size:55px}.intro-header .post-heading .subheading{font-size:30px;color:#FFF}}.post-preview>a{color:#404040}.post-preview>a:hover,.post-preview>a:focus{text-decoration:none;color:#0085a1}.post-preview>a>.post-title{font-size:30px;margin-top:30px;margin-bottom:10px}.post-preview>a>.post-subtitle{margin:0;font-weight:300;margin-bottom:10px}.post-preview>.post-meta{color:gray;font-size:18px;font-style:italic;margin-top:0}.post-preview>.post-meta>a{text-decoration:none;color:#404040}.post-preview>.post-meta>a:hover,.post-preview>.post-meta>a:focus{color:#0085a1;text-decoration:underline}@media only screen and (min-width:768px){.post-preview>a>.post-title{font-size:36px}}.section-heading{font-size:36px;margin-top:60px;font-weight:700}.caption{text-align:center;font-size:14px;padding:10px;font-style:italic;margin:0;display:block;border-bottom-right-radius:5px;border-bottom-left-radius:5px}footer{padding:50px 0 65px}footer .list-inline{margin:0;padding:0}footer .copyright{font-size:14px;text-align:center;margin-bottom:0}.floating-label-form-group{font-size:14px;position:relative;margin-bottom:0;padding-bottom:.5em;border-bottom:1px solid #eee}.floating-label-form-group input,.floating-label-form-group textarea{z-index:1;position:relative;padding-right:0;padding-left:0;border:none;border-radius:0;font-size:1.5em;background:0 0;box-shadow:none!important;resize:none}.floating-label-form-group label{display:block;z-index:0;position:relative;top:2em;margin:0;font-size:.85em;line-height:1.764705882em;vertical-align:middle;vertical-align:baseline;opacity:0;-webkit-transition:top .3s ease,opacity .3s ease;-moz-transition:top .3s ease,opacity .3s ease;-ms-transition:top .3s ease,opacity .3s ease;transition:top .3s ease,opacity .3s ease}.floating-label-form-group::not(:first-child){padding-left:14px;border-left:1px solid #eee}.floating-label-form-group-with-value label{top:0;opacity:1}.floating-label-form-group-with-focus label{color:#0085a1}form .row:first-child .floating-label-form-group{border-top:1px solid #eee}.btn{font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;text-transform:uppercase;font-size:14px;font-weight:800;letter-spacing:1px;border-radius:0;padding:15px 25px}.btn-lg{font-size:16px;padding:25px 35px}.btn-default:hover,.btn-default:focus{background-color:#0085a1;border:1px solid #0085a1;color:#fff}.pager{margin:20px 0 0}.pager li>a,.pager li>span{font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;text-transform:uppercase;font-size:14px;font-weight:800;letter-spacing:1px;padding:15px 25px;background-color:#fff;border-radius:0}.pager li>a:hover,.pager li>a:focus{color:#fff;background-color:#0085a1;border:1px solid #0085a1}.pager .disabled>a,.pager .disabled>a:hover,.pager .disabled>a:focus,.pager .disabled>span{color:gray;background-color:#404040;cursor:not-allowed}::-moz-selection{color:#fff;text-shadow:none;background:#0085a1}::selection{color:#fff;text-shadow:none;background:#0085a1}img::selection{color:#fff;background:0 0}img::-moz-selection{color:#fff;background:0 0}body{webkit-tap-highlight-color:#0085a1}
EOD;

$filename = 'Views/css/clean-blog.min.css';
$dirname = dirname($filename);
if (!is_dir($dirname))
{
    mkdir($dirname, 0755, true);
}
$cssFile = fopen($filename, 'w');
fwrite($cssFile, $cssLib1);
fclose($cssFile);
// Creation Status
(file_exists('Views/css/clean-blog.min.css'))? $cssNot = "Ok" : $cssNot = "Error: File not exists.";
$invStatus["Creating CSS files."] = $cssNot;


/**
 * JS Library needed
 */
$jsLib1 = <<<'EOD'
/*!
 * Clean Blog v1.0.0 (http://startbootstrap.com)
 * Copyright 2014 Start Bootstrap
 * Licensed under Apache 2.0 (https://github.com/IronSummitMedia/startbootstrap/blob/gh-pages/LICENSE)
 */

$(function(){$("#contactFrom input,#contactForm textarea").jqBootstrapValidation({preventSubmit:!0,submitError:function(){},submitSuccess:function(a,b){b.preventDefault();var c=$("input#name").val(),d=$("input#email").val(),e=$("input#phone").val(),f=$("textarea#message").val(),g=c;g.indexOf(" ")>=0&&(g=c.split(" ").slice(0,-1).join(" ")),$.ajax({url:"././mail/contact_me.php",type:"POST",data:{name:c,phone:e,email:d,message:f},cache:!1,success:function(){$("#success").html("<div class='alert alert-success'>"),$("#success > .alert-success").html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;").append("</button>"),$("#success > .alert-success").append("<strong>Your message has been sent. </strong>"),$("#success > .alert-success").append("</div>"),$("#contactForm").trigger("reset")},error:function(){$("#success").html("<div class='alert alert-danger'>"),$("#success > .alert-danger").html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;").append("</button>"),$("#success > .alert-danger").append("<strong>Sorry "+g+", it seems that my mail server is not responding. Please try again later!"),$("#success > .alert-danger").append("</div>"),$("#contactForm").trigger("reset")}})},filter:function(){return $(this).is(":visible")}}),$('a[data-toggle="tab"]').click(function(a){a.preventDefault(),$(this).tab("show")})}),$("#name").focus(function(){$("#success").html("")}),function(a){function b(a){return new RegExp("^"+a+"$")}function c(a,b){for(var c=Array.prototype.slice.call(arguments).splice(2),d=a.split("."),e=d.pop(),f=0;f<d.length;f++)b=b[d[f]];return b[e].apply(this,c)}var d=[],e={options:{prependExistingHelpBlock:!1,sniffHtml:!0,preventSubmit:!0,submitError:!1,submitSuccess:!1,semanticallyStrict:!1,autoAdd:{helpBlocks:!0},filter:function(){return!0}},methods:{init:function(b){var c=a.extend(!0,{},e);c.options=a.extend(!0,c.options,b);var h=this,i=a.unique(h.map(function(){return a(this).parents("form")[0]}).toArray());return a(i).bind("submit",function(b){var d=a(this),e=0,f=d.find("input,textarea,select").not("[type=submit],[type=image]").filter(c.options.filter);f.trigger("submit.validation").trigger("validationLostFocus.validation"),f.each(function(b,c){var d=a(c),f=d.parents(".form-group").first();f.hasClass("warning")&&(f.removeClass("warning").addClass("error"),e++)}),f.trigger("validationLostFocus.validation"),e?(c.options.preventSubmit&&b.preventDefault(),d.addClass("error"),a.isFunction(c.options.submitError)&&c.options.submitError(d,b,f.jqBootstrapValidation("collectErrors",!0))):(d.removeClass("error"),a.isFunction(c.options.submitSuccess)&&c.options.submitSuccess(d,b))}),this.each(function(){var b=a(this),e=b.parents(".form-group").first(),h=e.find(".help-block").first(),i=b.parents("form").first(),j=[];if(!h.length&&c.options.autoAdd&&c.options.autoAdd.helpBlocks&&(h=a('<div class="help-block" />'),e.find(".controls").append(h),d.push(h[0])),c.options.sniffHtml){var k="";if(void 0!==b.attr("pattern")&&(k="Not in the expected format<!-- data-validation-pattern-message to override -->",b.data("validationPatternMessage")&&(k=b.data("validationPatternMessage")),b.data("validationPatternMessage",k),b.data("validationPatternRegex",b.attr("pattern"))),void 0!==b.attr("max")||void 0!==b.attr("aria-valuemax")){var l=b.attr(void 0!==b.attr("max")?"max":"aria-valuemax");k="Too high: Maximum of '"+l+"'<!-- data-validation-max-message to override -->",b.data("validationMaxMessage")&&(k=b.data("validationMaxMessage")),b.data("validationMaxMessage",k),b.data("validationMaxMax",l)}if(void 0!==b.attr("min")||void 0!==b.attr("aria-valuemin")){var m=b.attr(void 0!==b.attr("min")?"min":"aria-valuemin");k="Too low: Minimum of '"+m+"'<!-- data-validation-min-message to override -->",b.data("validationMinMessage")&&(k=b.data("validationMinMessage")),b.data("validationMinMessage",k),b.data("validationMinMin",m)}void 0!==b.attr("maxlength")&&(k="Too long: Maximum of '"+b.attr("maxlength")+"' characters<!-- data-validation-maxlength-message to override -->",b.data("validationMaxlengthMessage")&&(k=b.data("validationMaxlengthMessage")),b.data("validationMaxlengthMessage",k),b.data("validationMaxlengthMaxlength",b.attr("maxlength"))),void 0!==b.attr("minlength")&&(k="Too short: Minimum of '"+b.attr("minlength")+"' characters<!-- data-validation-minlength-message to override -->",b.data("validationMinlengthMessage")&&(k=b.data("validationMinlengthMessage")),b.data("validationMinlengthMessage",k),b.data("validationMinlengthMinlength",b.attr("minlength"))),(void 0!==b.attr("required")||void 0!==b.attr("aria-required"))&&(k=c.builtInValidators.required.message,b.data("validationRequiredMessage")&&(k=b.data("validationRequiredMessage")),b.data("validationRequiredMessage",k)),void 0!==b.attr("type")&&"number"===b.attr("type").toLowerCase()&&(k=c.builtInValidators.number.message,b.data("validationNumberMessage")&&(k=b.data("validationNumberMessage")),b.data("validationNumberMessage",k)),void 0!==b.attr("type")&&"email"===b.attr("type").toLowerCase()&&(k="Not a valid email address<!-- data-validator-validemail-message to override -->",b.data("validationValidemailMessage")?k=b.data("validationValidemailMessage"):b.data("validationEmailMessage")&&(k=b.data("validationEmailMessage")),b.data("validationValidemailMessage",k)),void 0!==b.attr("minchecked")&&(k="Not enough options checked; Minimum of '"+b.attr("minchecked")+"' required<!-- data-validation-minchecked-message to override -->",b.data("validationMincheckedMessage")&&(k=b.data("validationMincheckedMessage")),b.data("validationMincheckedMessage",k),b.data("validationMincheckedMinchecked",b.attr("minchecked"))),void 0!==b.attr("maxchecked")&&(k="Too many options checked; Maximum of '"+b.attr("maxchecked")+"' required<!-- data-validation-maxchecked-message to override -->",b.data("validationMaxcheckedMessage")&&(k=b.data("validationMaxcheckedMessage")),b.data("validationMaxcheckedMessage",k),b.data("validationMaxcheckedMaxchecked",b.attr("maxchecked")))}void 0!==b.data("validation")&&(j=b.data("validation").split(",")),a.each(b.data(),function(a){var b=a.replace(/([A-Z])/g,",$1").split(",");"validation"===b[0]&&b[1]&&j.push(b[1])});var n=j,o=[];do a.each(j,function(a,b){j[a]=f(b)}),j=a.unique(j),o=[],a.each(n,function(d,e){if(void 0!==b.data("validation"+e+"Shortcut"))a.each(b.data("validation"+e+"Shortcut").split(","),function(a,b){o.push(b)});else if(c.builtInValidators[e.toLowerCase()]){var g=c.builtInValidators[e.toLowerCase()];"shortcut"===g.type.toLowerCase()&&a.each(g.shortcut.split(","),function(a,b){b=f(b),o.push(b),j.push(b)})}}),n=o;while(n.length>0);var p={};a.each(j,function(d,e){var g=b.data("validation"+e+"Message"),h=void 0!==g,i=!1;if(g=g?g:"'"+e+"' validation failed <!-- Add attribute 'data-validation-"+e.toLowerCase()+"-message' to input to change this message -->",a.each(c.validatorTypes,function(c,d){void 0===p[c]&&(p[c]=[]),i||void 0===b.data("validation"+e+f(d.name))||(p[c].push(a.extend(!0,{name:f(d.name),message:g},d.init(b,e))),i=!0)}),!i&&c.builtInValidators[e.toLowerCase()]){var j=a.extend(!0,{},c.builtInValidators[e.toLowerCase()]);h&&(j.message=g);var k=j.type.toLowerCase();"shortcut"===k?i=!0:a.each(c.validatorTypes,function(c,d){void 0===p[c]&&(p[c]=[]),i||k!==c.toLowerCase()||(b.data("validation"+e+f(d.name),j[d.name.toLowerCase()]),p[k].push(a.extend(j,d.init(b,e))),i=!0)})}i||a.error("Cannot find validation info for '"+e+"'")}),h.data("original-contents",h.data("original-contents")?h.data("original-contents"):h.html()),h.data("original-role",h.data("original-role")?h.data("original-role"):h.attr("role")),e.data("original-classes",e.data("original-clases")?e.data("original-classes"):e.attr("class")),b.data("original-aria-invalid",b.data("original-aria-invalid")?b.data("original-aria-invalid"):b.attr("aria-invalid")),b.bind("validation.validation",function(d,e){var f=g(b),h=[];return a.each(p,function(d,g){(f||f.length||e&&e.includeEmpty||c.validatorTypes[d].blockSubmit&&e&&e.submitting)&&a.each(g,function(a,e){c.validatorTypes[d].validate(b,f,e)&&h.push(e.message)})}),h}),b.bind("getValidators.validation",function(){return p}),b.bind("submit.validation",function(){return b.triggerHandler("change.validation",{submitting:!0})}),b.bind(["keyup","focus","blur","click","keydown","keypress","change"].join(".validation ")+".validation",function(d,f){var j=g(b),k=[];e.find("input,textarea,select").each(function(c,d){var e=k.length;if(a.each(a(d).triggerHandler("validation.validation",f),function(a,b){k.push(b)}),k.length>e)a(d).attr("aria-invalid","true");else{var g=b.data("original-aria-invalid");a(d).attr("aria-invalid",void 0!==g?g:!1)}}),i.find("input,select,textarea").not(b).not('[name="'+b.attr("name")+'"]').trigger("validationLostFocus.validation"),k=a.unique(k.sort()),k.length?(e.removeClass("success error").addClass("warning"),h.html(c.options.semanticallyStrict&&1===k.length?k[0]+(c.options.prependExistingHelpBlock?h.data("original-contents"):""):'<ul role="alert"><li>'+k.join("</li><li>")+"</li></ul>"+(c.options.prependExistingHelpBlock?h.data("original-contents"):""))):(e.removeClass("warning error success"),j.length>0&&e.addClass("success"),h.html(h.data("original-contents"))),"blur"===d.type&&e.removeClass("success")}),b.bind("validationLostFocus.validation",function(){e.removeClass("success")})})},destroy:function(){return this.each(function(){var b=a(this),c=b.parents(".form-group").first(),e=c.find(".help-block").first();b.unbind(".validation"),e.html(e.data("original-contents")),c.attr("class",c.data("original-classes")),b.attr("aria-invalid",b.data("original-aria-invalid")),e.attr("role",b.data("original-role")),d.indexOf(e[0])>-1&&e.remove()})},collectErrors:function(){var b={};return this.each(function(c,d){var e=a(d),f=e.attr("name"),g=e.triggerHandler("validation.validation",{includeEmpty:!0});b[f]=a.extend(!0,g,b[f])}),a.each(b,function(a,c){0===c.length&&delete b[a]}),b},hasErrors:function(){var b=[];return this.each(function(c,d){b=b.concat(a(d).triggerHandler("getValidators.validation")?a(d).triggerHandler("validation.validation",{submitting:!0}):[])}),b.length>0},override:function(b){e=a.extend(!0,e,b)}},validatorTypes:{callback:{name:"callback",init:function(a,b){return{validatorName:b,callback:a.data("validation"+b+"Callback"),lastValue:a.val(),lastValid:!0,lastFinished:!0}},validate:function(a,b,d){if(d.lastValue===b&&d.lastFinished)return!d.lastValid;if(d.lastFinished===!0){d.lastValue=b,d.lastValid=!0,d.lastFinished=!1;var e=d,f=a;c(d.callback,window,a,b,function(a){e.lastValue===a.value&&(e.lastValid=a.valid,a.message&&(e.message=a.message),e.lastFinished=!0,f.data("validation"+e.validatorName+"Message",e.message),setTimeout(function(){f.trigger("change.validation")},1))})}return!1}},ajax:{name:"ajax",init:function(a,b){return{validatorName:b,url:a.data("validation"+b+"Ajax"),lastValue:a.val(),lastValid:!0,lastFinished:!0}},validate:function(b,c,d){return""+d.lastValue==""+c&&d.lastFinished===!0?d.lastValid===!1:(d.lastFinished===!0&&(d.lastValue=c,d.lastValid=!0,d.lastFinished=!1,a.ajax({url:d.url,data:"value="+c+"&field="+b.attr("name"),dataType:"json",success:function(a){""+d.lastValue==""+a.value&&(d.lastValid=!!a.valid,a.message&&(d.message=a.message),d.lastFinished=!0,b.data("validation"+d.validatorName+"Message",d.message),setTimeout(function(){b.trigger("change.validation")},1))},failure:function(){d.lastValid=!0,d.message="ajax call failed",d.lastFinished=!0,b.data("validation"+d.validatorName+"Message",d.message),setTimeout(function(){b.trigger("change.validation")},1)}})),!1)}},regex:{name:"regex",init:function(a,c){return{regex:b(a.data("validation"+c+"Regex"))}},validate:function(a,b,c){return!c.regex.test(b)&&!c.negative||c.regex.test(b)&&c.negative}},required:{name:"required",init:function(){return{}},validate:function(a,b,c){return!(0!==b.length||c.negative)||!!(b.length>0&&c.negative)},blockSubmit:!0},match:{name:"match",init:function(a,b){var c=a.parents("form").first().find('[name="'+a.data("validation"+b+"Match")+'"]').first();return c.bind("validation.validation",function(){a.trigger("change.validation",{submitting:!0})}),{element:c}},validate:function(a,b,c){return b!==c.element.val()&&!c.negative||b===c.element.val()&&c.negative},blockSubmit:!0},max:{name:"max",init:function(a,b){return{max:a.data("validation"+b+"Max")}},validate:function(a,b,c){return parseFloat(b,10)>parseFloat(c.max,10)&&!c.negative||parseFloat(b,10)<=parseFloat(c.max,10)&&c.negative}},min:{name:"min",init:function(a,b){return{min:a.data("validation"+b+"Min")}},validate:function(a,b,c){return parseFloat(b)<parseFloat(c.min)&&!c.negative||parseFloat(b)>=parseFloat(c.min)&&c.negative}},maxlength:{name:"maxlength",init:function(a,b){return{maxlength:a.data("validation"+b+"Maxlength")}},validate:function(a,b,c){return b.length>c.maxlength&&!c.negative||b.length<=c.maxlength&&c.negative}},minlength:{name:"minlength",init:function(a,b){return{minlength:a.data("validation"+b+"Minlength")}},validate:function(a,b,c){return b.length<c.minlength&&!c.negative||b.length>=c.minlength&&c.negative}},maxchecked:{name:"maxchecked",init:function(a,b){var c=a.parents("form").first().find('[name="'+a.attr("name")+'"]');return c.bind("click.validation",function(){a.trigger("change.validation",{includeEmpty:!0})}),{maxchecked:a.data("validation"+b+"Maxchecked"),elements:c}},validate:function(a,b,c){return c.elements.filter(":checked").length>c.maxchecked&&!c.negative||c.elements.filter(":checked").length<=c.maxchecked&&c.negative},blockSubmit:!0},minchecked:{name:"minchecked",init:function(a,b){var c=a.parents("form").first().find('[name="'+a.attr("name")+'"]');return c.bind("click.validation",function(){a.trigger("change.validation",{includeEmpty:!0})}),{minchecked:a.data("validation"+b+"Minchecked"),elements:c}},validate:function(a,b,c){return c.elements.filter(":checked").length<c.minchecked&&!c.negative||c.elements.filter(":checked").length>=c.minchecked&&c.negative},blockSubmit:!0}},builtInValidators:{email:{name:"Email",type:"shortcut",shortcut:"validemail"},validemail:{name:"Validemail",type:"regex",regex:"[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}",message:"Not a valid email address<!-- data-validator-validemail-message to override -->"},passwordagain:{name:"Passwordagain",type:"match",match:"password",message:"Does not match the given password<!-- data-validator-paswordagain-message to override -->"},positive:{name:"Positive",type:"shortcut",shortcut:"number,positivenumber"},negative:{name:"Negative",type:"shortcut",shortcut:"number,negativenumber"},number:{name:"Number",type:"regex",regex:"([+-]?\\d+(\\.\\d*)?([eE][+-]?[0-9]+)?)?",message:"Must be a number<!-- data-validator-number-message to override -->"},integer:{name:"Integer",type:"regex",regex:"[+-]?\\d+",message:"No decimal places allowed<!-- data-validator-integer-message to override -->"},positivenumber:{name:"Positivenumber",type:"min",min:0,message:"Must be a positive number<!-- data-validator-positivenumber-message to override -->"},negativenumber:{name:"Negativenumber",type:"max",max:0,message:"Must be a negative number<!-- data-validator-negativenumber-message to override -->"},required:{name:"Required",type:"required",message:"This is required<!-- data-validator-required-message to override -->"},checkone:{name:"Checkone",type:"minchecked",minchecked:1,message:"Check at least one option<!-- data-validation-checkone-message to override -->"}}},f=function(a){return a.toLowerCase().replace(/(^|\s)([a-z])/g,function(a,b,c){return b+c.toUpperCase()})},g=function(b){var c=b.val(),d=b.attr("type");return"checkbox"===d&&(c=b.is(":checked")?c:""),"radio"===d&&(c=a('input[name="'+b.attr("name")+'"]:checked').length>0?c:""),c};a.fn.jqBootstrapValidation=function(b){return e.methods[b]?e.methods[b].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof b&&b?(a.error("Method "+b+" does not exist on jQuery.jqBootstrapValidation"),null):e.methods.init.apply(this,arguments)},a.jqBootstrapValidation=function(){a(":input").not("[type=image],[type=submit]").jqBootstrapValidation.apply(this,arguments)}}(jQuery),$(function(){$("body").on("input propertychange",".floating-label-form-group",function(a){$(this).toggleClass("floating-label-form-group-with-value",!!$(a.target).val())}).on("focus",".floating-label-form-group",function(){$(this).addClass("floating-label-form-group-with-focus")}).on("blur",".floating-label-form-group",function(){$(this).removeClass("floating-label-form-group-with-focus")})}),jQuery(document).ready(function(a){var b=1170;if(a(window).width()>b){var c=a(".navbar-custom").height();a(window).on("scroll",{previousTop:0},function(){var b=a(window).scrollTop();b<this.previousTop?b>0&&a(".navbar-custom").hasClass("is-fixed")?a(".navbar-custom").addClass("is-visible"):a(".navbar-custom").removeClass("is-visible is-fixed"):(a(".navbar-custom").removeClass("is-visible"),b>c&&!a(".navbar-custom").hasClass("is-fixed")&&a(".navbar-custom").addClass("is-fixed")),this.previousTop=b})}});
EOD;

$filename = 'Views/js/clean-blog.min.js';
$dirname = dirname($filename);
if (!is_dir($dirname))
{
    mkdir($dirname, 0755, true);
}
$jsFile1 = fopen($filename, 'w');
fwrite($jsFile1, $jsLib1);
fclose($jsFile1);
// Creation Status
(file_exists('Views/js/clean-blog.min.js'))? $jsNot = "Ok" : $jsNot = "Error: File not exists.";
$invStatus["Creating JS Blog files."] = $jsNot;

/**
 * POST Body Views/Default/post.php
 */
$postView = <<<'EOD'
    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('<?=$featuredImage?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1><?=$title?></h1>
                        <h2 class="subheading"><?=$subtitle?></h2>
                        <span class="meta">Posted by <a href="#">Start Bootstrap</a> on January 1, 2016</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <?=$content?>
                </div>
            </div>
        </div>
    </article>
EOD;
$postFile = fopen('Views/Default/post.php', 'w');
fwrite($postFile, $postView);
fclose($postFile);
// Creation Status
(file_exists('Views/Default/post.php'))? $postNot = "Ok" : $postNot = "Error: File not exists.";
$invStatus["Creating POST page."] = $postNot;


/**
 * POST Body Views/Default/about.php
 */
$aboutView = <<<'EOD'
    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('https://static.pexels.com/photos/103875/pexels-photo-103875.jpeg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="page-heading">
                        <h1><?=$title?></h1>
                        <hr class="small">
                        <span class="subheading">This is what I do.</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe nostrum ullam eveniet pariatur voluptates odit, fuga atque ea nobis sit soluta odio, adipisci quas excepturi maxime quae totam ducimus consectetur?</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius praesentium recusandae illo eaque architecto error, repellendus iusto reprehenderit, doloribus, minus sunt. Numquam at quae voluptatum in officia voluptas voluptatibus, minus!</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum molestiae debitis nobis, quod sapiente qui voluptatum, placeat magni repudiandae accusantium fugit quas labore non rerum possimus, corrupti enim modi! Et.</p>
            </div>
        </div>
    </div>
EOD;
$aboutFile = fopen('Views/Default/about.php', 'w');
fwrite($aboutFile, $aboutView);
fclose($aboutFile);
// Creation Status
(file_exists('Views/Default/about.php'))? $postNot = "Ok" : $postNot = "Error: File not exists.";
$invStatus["Creating ABOUT page."] = $postNot;


/**
 * POSTS Body Views/Default/posts.php
 */
$postsView = <<<'EOD'
    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('https://static.pexels.com/photos/103875/pexels-photo-103875.jpeg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1><?=$title?></h1>
                        <hr class="small">
                        <span class="subheading"><?=$seo_description?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <?php
                foreach ($posts as $key => $post) {
                ?>
                <div class="post-preview">
                    <a href="<?=$post['url']?>">
                        <h2 class="post-title">
                            <?=$post['title']?>
                        </h2>
                        <h3 class="post-subtitle">
                            <?=$post['subtitle']?>
                        </h3>
                    </a>
                    <p class="post-meta">Posted by <a href="#">Start Bootstrap</a> on September 24, 2014</p>
                </div>
                <hr>
                <?php
                }
                ?>
                <!-- Pager -->
                <ul class="pager">
                    <li class="next">
                        <a href="#">Older Posts &rarr;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
EOD;
$postsFile = fopen('Views/Default/posts.php', 'w');
fwrite($postsFile, $postsView);
fclose($postsFile);
// Creation Status
(file_exists('Views/Default/posts.php'))? $postNot = "Ok" : $postNot = "Error: File not exists.";
$invStatus["Creating POSTS page."] = $postNot;


/**
 * ABOUT Body Views/Default/contact.php
 */
$contactView = <<<'EOD'
        <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('https://static.pexels.com/photos/103875/pexels-photo-103875.jpeg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="page-heading">
                        <h1>Contact Me</h1>
                        <hr class="small">
                        <span class="subheading">Have questions? I have answers (maybe).</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <?php if (isset($flash['error'])): ?>
                <div class="alert alert-danger"><span class="glyphicon glyphicon-alert"></span><strong> <?php echo $flash['error'] ?></strong></div>
                <?php endif;
                      if (isset($flash['message'])): ?>
                <div class="alert alert-success"><strong><span class="glyphicon glyphicon-send"></span> <?php echo $flash['message'] ?></strong></div>	 
                <?php endif; ?>
                <p>Want to get in touch with me? Fill out the form below to send me a message and I will try to get back to you within 24 hours!</p>
                <!-- Contact Form - Enter your email address on line 19 of the mail/contact_me.php file to make this form work. -->
                <!-- WARNING: Some web hosts do not allow emails to be sent through forms to common mail hosts like Gmail or Yahoo. It's recommended that you use a private domain email address! -->
                <!-- NOTE: To use the contact form, your site must be on a live web host with PHP! The form will not work locally! -->
                <form name="sentMessage" id="contactForm" novalidate>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Name</label>
                            <input type="text" class="form-control" placeholder="Name" id="name" required data-validation-required-message="Please enter your name.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Email Address</label>
                            <input type="email" class="form-control" placeholder="Email Address" id="email" required data-validation-required-message="Please enter your email address.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Phone Number</label>
                            <input type="tel" class="form-control" placeholder="Phone Number" id="phone" required data-validation-required-message="Please enter your phone number.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Message</label>
                            <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="Please enter a message."></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div id="success"></div>
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <button type="submit" class="btn btn-default">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
EOD;
$contactFile = fopen('Views/Default/contact.php', 'w');
fwrite($contactFile, $contactView);
fclose($contactFile);
// Creation Status
(file_exists('Views/Default/contact.php'))? $postNot = "Ok" : $postNot = "Error: File not exists.";
$invStatus["Creating CONTACT page."] = $postNot;

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
    <meta name="description" content="<?=$seo_title?>">
    <meta name="author" content="<?=$seo_description?>">

    <title><?=$title?> - Clean Blog</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=basePath()?>/Adive/Internal/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=asset('clean-blog.min.css')?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=basePath()?>/Adive/Internal/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=path('home')?>">Start Bootstrap</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?=path('home')?>">Home</a>
                    </li>
                    <li>
                        <a href="<?=path('about')?>">About</a>
                    </li>
                    <li>
                        <a href="<?=path('contact')?>">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <?php require_once $_SESSION['path.now'].'.php'; ?>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; Your Website 2016</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="<?=iasset('jquery.min.js')?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=iasset('bootstrap.min.js')?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=asset('clean-blog.min.js')?>"></script>

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
 * @Route(GET) Blog Home Page.
 */
$API->get('/', 
    function() use($API, $db) {
        pathActive('/');
        $postsQ = $db->prepare("SELECT * FROM posts_pos ORDER BY creation_date DESC");
        $postsQ->execute();
        $posts = $postsQ->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Default:posts', array(
            'posts' => $posts,
            'title' => 'Clean Blog',
            'seo_title' => 'Clean Blog',
            'seo_description' => 'A Clean Blog Theme by Start Bootstrap',
        ));
    }
)->name('home');

/**
 * @Route(GET) Blog About Page.
 */
$API->get('/about', 
    function() use($API, $db) {
        pathActive('about');
        $postsQ = $db->prepare("SELECT * FROM posts_pos ORDER BY creation_date DESC");
        $postsQ->execute();
        $posts = $postsQ->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Default:about', array(
            'posts' => $posts,
            'title' => 'About',
            'seo_title' => 'About Clean Blog',
            'seo_description' => 'About Clean Blog by Bootstrap',
        ));
    }
)->name('about');

/**
 * @Route(GET) Contact Page.
 */
$API->get('/contact', 
    function() use($API, $db) {
        pathActive('contact');
        $postsQ = $db->prepare("SELECT * FROM posts_pos ORDER BY creation_date DESC");
        $postsQ->execute();
        $posts = $postsQ->fetchAll(PDO::FETCH_ASSOC);

        $API->render('Default:contact', array(
            'posts' => $posts,
            'title' => 'Contact Me',
            'seo_title' => 'Contact Me',
            'seo_description' => 'Have questions? I have answers (maybe).',
        ));
    }
)->name('contact');

/**
 * @Route(POST) Blog Contact Page
 */
$API->post('/contact', 
    function() use($API, $db) {
        $formData=$API->request;
        $inputA = $formData->post('InputRealA');
        $inputB = $formData->post('InputRealB');
        $inputReal = $formData->post('InputReal');
        
        if($inputA+$inputB==$inputReal){
            $for     = 'nobody@example.com';
            $subject = 'Web Contact Form';
            $message = 'Name: '.$formData->post('InputName').'\n'
                     . 'Email: '.$formData->post('InputEmail').'\n'
                     . 'Message: '.$formData->post('InputMessage').'\n';
            $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: '.$formData->post('InputEmail').'' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            //$status = mail($for, $subject, $message, $headers);
            $status = true;
            $message = 'Error! Mail has not send, Please check the inputs.';
        } else {
            $status = false;
            $message = 'Error! Wrong SPAM Checker. Try it again.';
        }
        ($status)? $API->flash('message','Success! Message sent.')
                 : $API->flash('error',$message);
        
        $API->redirect($API->urlFor('contact'));
    }
);

/**
 * @Route(GET) Blog POST.
 */
$API->get('/{permalink}', 
    function($permalink) use($API, $db) {
        pathActive($permalink);
        $postsQ = $db->prepare("SELECT * FROM posts_pos WHERE url=:url");
        $postsQ->execute(array(
            'url' => $permalink
        ));
        $post = $postsQ->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Default:post', array(
            'title' => $post[0]['title'],
            'subtitle' => $post[0]['subtitle'],
            'featuredImage' => $post[0]['image'],
            'content' => $post[0]['content'],
            'url' => $post[0]['url'],
            'image' => $post[0]['image'],
            'seo_title' => $post[0]['seo_title'],
            'seo_description' => $post[0]['seo_description'],
        ));
    }
);
EOD;

$controllerFile = fopen('Controller/Default.php', 'w');
fwrite($controllerFile, $controllerView);
fclose($controllerFile);
// Creation Status
(file_exists('Controller/Default.php'))? $contNot = "Ok" : $contNot = "Error: File not exists.";
$invStatus["Updating Default Controller."] = $contNot;
