<?php

function formatPrice($vlprice)
{
    if (!$vlprice > 0) $vlprice = 0;
    return number_format($vlprice, 2, ",", ".");
}

function formatDate($date)
{
    return date("d/m/Y",strtotime($date));
}

function isKit($boolean)
{
	if($boolean ==1)
		return "<span class='btn btn-success btn-sm'>SIM</span>";
	return "<span class='btn btn-warning btn-sm'>NÃO<span>";
}