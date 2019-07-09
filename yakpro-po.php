<?php
// YAK Pro - Php Obfuscator: Config File
// Do not delete the previous line! it's a magic string for config file!
//========================================================================
// Author:  Pascal KISSIAN
// Resume:  http://pascal.kissian.net
//
// CopyRight (c) 2015 Pascal KISSIAN
//
// Published under the MIT License
//          Consider it as a proof of concept!
//          No warranty of any kind.
//          Use and abuse at your own risks.
//========================================================================
// when we use the word ignore, that means that it is ignored during the obfuscation process (i.e. not obfuscated)

ini_set('xdebug.max_nesting_level', 3000);
ini_set('memory_limit', '-1');

$conf->t_ignore_pre_defined_classes     = 'all';        // 'all' (default value) , 'none',  or array of pre-defined classes that you use in your software:
                                                        //      ex: array('Exception', 'PDO', 'PDOStatement', 'PDOException');
                                                        // As instantiation is done at runtime, it is impossible to statically determinate when a method call is detected, on which class the object belong.
                                                        // so, all method names that exists in a pre_defined_class to ignore are ignored within every classes.
                                                        // if you have some method names in your classes that have the same name that a predefine class method, it will not be obfuscated.
                                                        // you can limit the number of method names to ignore by providing an array of the pre-defined classes you really use in your software!
                                                        // same behaviour for properties...

$conf->t_ignore_constants               = null;         // array where values are names to ignore.
$conf->t_ignore_variables               = null;         // array where values are names to ignore.
$conf->t_ignore_functions               = null;         // array where values are names to ignore.
$conf->t_ignore_class_constants         = null;         // array where values are names to ignore.
$conf->t_ignore_methods                 = null;         // array where values are names to ignore.
$conf->t_ignore_properties              = null;         // array where values are names to ignore.
$conf->t_ignore_classes                 = null;         // array where values are names to ignore.
$conf->t_ignore_interfaces              = null;         // array where values are names to ignore.
$conf->t_ignore_traits                  = null;         // array where values are names to ignore.
$conf->t_ignore_namespaces              = null;         // array where values are names to ignore.
$conf->t_ignore_labels                  = null;         // array where values are names to ignore.

$conf->t_ignore_constants_prefix        = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_variables_prefix        = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_functions_prefix        = null;         // array where values are prefix of names to ignore.

$conf->t_ignore_class_constants_prefix  = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_properties_prefix       = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_methods_prefix          = null;         // array where values are prefix of names to ignore.

$conf->t_ignore_classes_prefix          = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_interfaces_prefix       = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_traits_prefix           = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_namespaces_prefix       = null;         // array where values are prefix of names to ignore.
$conf->t_ignore_labels_prefix           = null;         // array where values are prefix of names to ignore.


$conf->scramble_mode                    = 'identifier'; // allowed modes are 'identifier', 'hexa', 'numeric'
$conf->scramble_length                  = 5;            // min length of scrambled names (min = 2; max = 16 for identifier, 32 for hexa and numeric)

$conf->t_obfuscate_php_extension        = array('php'); // array where values are extensions of php files to be obfuscated.

$conf->obfuscate_constant_name          = false;         // self explanatory
$conf->obfuscate_variable_name          = false;         // self explanatory
$conf->obfuscate_function_name          = false;         // self explanatory
$conf->obfuscate_class_name             = false;         // self explanatory
$conf->obfuscate_interface_name         = false;         // self explanatory
$conf->obfuscate_trait_name             = false;         // self explanatory
$conf->obfuscate_class_constant_name    = false;         // self explanatory
$conf->obfuscate_property_name          = false;         // self explanatory
$conf->obfuscate_method_name            = false;         // self explanatory
$conf->obfuscate_namespace_name         = false;         // self explanatory
$conf->obfuscate_label_name             = false;         // label: , goto label;  obfuscation
$conf->obfuscate_if_statement           = true;         // obfuscation of  if else elseif statements
$conf->obfuscate_loop_statement         = true;         // obfuscation of  for while do while statements
$conf->obfuscate_string_literal         = true;         // pseudo-obfuscation of  string literals

$conf->shuffle_stmts                    = true;         // shuffle chunks of statements!  disable this obfuscation (or minimize the number of chunks) if performance is important for you!
$conf->shuffle_stmts_min_chunk_size     = 1;            // minimum number of statements in a chunk! the min value is 1, that gives you the maximum of obfuscation ... and the minimum of performance...
$conf->shuffle_stmts_chunk_mode         = 'fixed';      // 'fixed' or 'ratio' in fixed mode, the chunk_size is always equal to the min chunk size!
$conf->shuffle_stmts_chunk_ratio        = 20;           // ratio > 1  100/ratio is the percentage of chunks in a statements sequence  ratio = 2 means 50%  ratio = 100 mins 1% ...
                                                        // if you increase the number of chunks, you increase also the obfuscation level ... and you increase also the performance overhead!

$conf->strip_indentation                = true;         // all your obfuscated code will be generated on a single line
$conf->abort_on_error                   = true;         // self explanatory
$conf->confirm                          = true;         // rfu : will answer Y on confirmation request (reserved for future use ... or not...)
$conf->silent                           = false;        // display or not Information level messages.

$conf->source_directory                 = __DIR__;
$conf->target_directory                 = __DIR__ . '/obfuscated';

$conf->t_keep = [];

$conf->t_skip = [
    '.git',
    '.idea',
    'bin/.phpunit',
    'obfuscated',
    'vendor',
    'yakpro-po',
];

$conf->user_comment                     = null;         // user comment to insert inside each obfuscated file

$conf->extract_comment_from_line        = null;         // when both 2 are set, each obfuscated file will contain an extract of the corresponding source file,
$conf->extract_comment_to_line          = null;         // starting from extract_comment_from_line number, and ending at extract_comment_to_line line number.
