<?php 
$insight_class = Request::string('insight_class');

echo HtmlBootstrap5::box("/insights-members/editMembers?insight_class=" . $insight_class, "Add new member", true);
echo $membersTable;
