<?php
// @command: balafon --run .test/db/jobresearch/db-update.fix.php
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use com\igkdev\projects\ForemJobDashboard\ModelUtilities\MainTaskModelUtility;
use IGK\System\Console\Logger;
use IGK\System\Database\DbQueryExpression;

$ctrl = ForemJobDashboardController::ctrl(true);
// + | change time stamp of a file 
$fuid = null;
$user = $ctrl::SignInUser('cbondje@igkdev.com');
if (($main = $ctrl::modelUtility("MainTask")) instanceof MainTaskModelUtility) {
    $cuser = $main->registerUser($user);
    $fuid = $cuser->id;
}
$desc = [<<<EOF
    Our mission is great open source

It is our mission to make open source software available to people everywhere. We create world-renowned software, impacting the lives of millions of people every day.

The web team works in a multi-disciplinary environment with visual designers, UX designers and other developers to bring exciting new web projects to life. We help and learn from each other and constantly strive to improve both our work and our processes.

Required skills and experience

Demonstrable experience of work on modern web applications
A strong understanding of HTML, CSS with SCSS and JavaScript
Experience with Javascript components libraries
Experience with TypeScript
Experience with responsive user interfaces for a wide range of devices and browsers
Experience with Git or other version control systems
Awareness of SEO best practice
Consideration of accessibility in all aspects of your work
A strong understanding of web performance in complex user interfaces
Ability to interact with UX, designers and server-side developers
Curiosity about technology and a thirst to learn
Based in EMEA timezones (Europe, the Middle East and Africa)
University degree or equivalent education

Useful experience if you have it

Familiarity with Linux desktop technologies
Server-side languages, such as Python or NodeJS
Experience working on a large scale React (with TypeScript) project
A history of open source contributions
Relevant work experience

Who you are

We want a Web Developer who loves what they do. You are passionate about web standards and keep abreast of new developments in our industry. You always look for opportunities to improve your skills. You like to show off what you're working on and also learn from others. You have strong attention to detail and value the design of a product as much as you value the code. You have strong communication skills and maybe even blog once in a while. You're aware of the latest CSS techniques but also know the limitations that developing for a broad audience can bring - actually, you embrace the challenge.
EOF,
];
$tab = Jobs::select_all([
    Jobs::FD_USER_ID => $fuid,
    "!" . Jobs::FD_DESCRIPTION => null
]);
foreach ($tab as $c) {
    $gdate = $c->description;
    // + remove all date 
    $gdate = preg_replace("/(\\d+-\\d+-\\d+)|(\\d+\/\\d+\/\\d+)/","", $gdate);
    $desc[] = $gdate;
}
if ($desc) {
    $tab = Jobs::select_all([
        Jobs::FD_USER_ID => $fuid,
        Jobs::FD_DESCRIPTION => null
    ]);
    foreach ($tab as $row) {
        $i = rand(0, count($desc) - 1);
        $row->summary = $desc[$i];
        $row->description = $desc[$i];
        $row->update();
    }
    Logger::info(count($tab));
}
Logger::success('done');
igk_exit();