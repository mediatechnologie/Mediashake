<?php
interface Factory
{
	public function __construct(Database $db);
	public static function create();
}