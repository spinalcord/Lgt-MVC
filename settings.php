<?php

// Errorhandling
error_reporting(E_ALL); // Show all errors
ini_set('display_errors', 1); // Show all errors in browser

// Language
const DEFAULT_LANGUAGE = 'en';
const LANGUAGE_FILES_PATH = 'app/languages/';

// Database setup
const DB_TYPE = 'sqlite';
const DB_NAME = 'Database.db'; // Databasename
const DB_PATH = 'app/database/'; // Only if Sqlite
const DB_USER = 'username'; // MySQL-User
const DB_PASSWORD = 'password'; // MySQL-Passwort
