<?php
set_time_limit(0); //remove limit execution timeout
error_reporting(E_ERROR | E_PARSE); //remove warning errors
include('Scraper.php'); //include the website scraper class

$scraper = new Scraper('https://www.insolvencydirect.bis.gov.uk/IESdatabase/viewbrobrusummary-new-sub.asp'); //declare scraper class

/*
 * Variables below are array output of scraped data from the url given i.e $names, $restrictions, $links, $dates
 */
$names = $scraper->findAll("/<b>Name:<\/b>(.*)\s*/"); //scrape all names data
$restrictions = $scraper->findAll("/<b>Restriction Length:<\/b>(.*)\s*/"); //scrape all years of restrictions data
$dates = $scraper->findAll("/<b>Date Submitted:<\/b>\s*(.*)/"); //scrape all the date submitted data
$links = $scraper->findAll('/<b>More Details:<\/b>\s*<a href="(.*)"/'); //scrape all the links from "Click here to view" data

/*
 * loop through all the links found and scrape available data within that link: This loop usually takes time to execute all the links
 */
$subpageDetails = array();
foreach($links as $key=>$link) {
	$subpage = new Scraper('https://www.insolvencydirect.bis.gov.uk/IESdatabase/'. str_replace(' ', '%20', $link)); //declare scraper class

	/*
	 * Variabled below are the details from the subpages from the "Click here to view" links
	 */
	$subpageDetails[$key]['date_of_birth'] = $subpage->findSingle("/<b>Date of Birth:<\/b>(.*)\s*/");
	$subpageDetails[$key]['date_of_order'] = $subpage->findSingle("/<b>Date of Order:<\/b>(.*)\s*/");
	$subpageDetails[$key]['length_of_order'] = $subpage->findSingle("/<b>Length of order:<\/b>(.*)\s*/");
	$subpageDetails[$key]['court_number'] = $subpage->findSingle("/<b>Court Number:<\/b>(.*)\s*/");
	$subpageDetails[$key]['last_known_address'] = $subpage->findSingle("/<b>Last Known Address:<\/b>(.*)\s*/");
	$subpageDetails[$key]['conduct'] = $subpage->findSingle("/<b>Conduct:<\/b>(.*)\s*/");
	$subpageDetails[$key]['info_date'] = $subpage->findSingle("/This\s*information\s*is\s*correct\s*as\s*at\s*(.*)\s*/");
}

print_r($names); //print the all the scraped names from the page
print_r($restrictions); //print all the scraped restrictions from the page
print_r($dates); //print all the scraped dates from the page
print_r($subpageDetails); //print all the details from the subpages of the links


