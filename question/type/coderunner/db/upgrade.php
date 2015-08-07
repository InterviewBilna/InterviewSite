<?php

require_once($CFG->dirroot . '/question/format/xml/format.php');
require_once($CFG->dirroot . '/lib/questionlib.php');
require_once($CFG->dirroot . '/lib/accesslib.php');

function xmldb_qtype_coderunner_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();
    if ($oldversion != 0 && $oldversion < 2013010201) {
        $table = new xmldb_table('quest_coderunner_options');
        $allornothingfield = new xmldb_field('all_or_nothing', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, true, null, '1');
        $dbman->add_field($table, $allornothingfield);

        $DB->set_field('quest_coderunner_options', 'coderunner_type', 'python3', array('coderunner_type' => 'python3_basic'));
        $DB->set_field('quest_coderunner_options', 'coderunner_type', 'python2', array('coderunner_type' => 'python2_basic'));
        $DB->delete_records('quest_coderunner_types', array('coderunner_type' => 'python3_basic'));
        $DB->delete_records('quest_coderunner_types', array('coderunner_type' => 'python2_basic'));
        upgrade_plugin_savepoint(true, 2013010201, 'qtype', 'coderunner');

    }

    if ($oldversion != 0 && $oldversion < 2013010202) {
        $table = new xmldb_table('quest_coderunner_testcases');
        $mark = new xmldb_field('mark', XMLDB_TYPE_NUMBER, '12', XMLDB_UNSIGNED, true, null, '1.0');
        $dbman->add_field($table, $mark);
        upgrade_plugin_savepoint(true, 2013010202, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013010301) {
        // Allow null sandbox and validator fields
        $table = new xmldb_table('quest_coderunner_types');
        $sandbox = new xmldb_field('sandbox', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, false, null);
        $validator = new xmldb_field('validator', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, false, null);
        $dbman->change_field_type($table, $sandbox);
        $dbman->change_field_type($table, $validator);
        upgrade_plugin_savepoint(true, 2013010301, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013010501) {
        // Add custom template option to question
        $table = new xmldb_table('quest_coderunner_options');
        $customTemplate = new xmldb_field('custom_template', XMLDB_TYPE_TEXT, 'medium', XMLDB_UNSIGNED, false, null);
        $dbman->add_field($table, $customTemplate);
        // Remove is_custom field from quest_coderunner_types
        $table = new xmldb_table('quest_coderunner_types');
        $fieldtodrop = new xmldb_field('is_custom', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, true, null, '0');
        $dbman->drop_field($table, $fieldtodrop);
        upgrade_plugin_savepoint(true, 2013010502, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013013001) {
        $table = new xmldb_table('quest_coderunner_testcases');
        $mark = new xmldb_field('mark', XMLDB_TYPE_NUMBER, '8,3', XMLDB_UNSIGNED, true, null, '1.0');
        $dbman->change_field_type($table, $mark);
        upgrade_plugin_savepoint(true, 2013013001, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013013101) {
        // Add show source option to question
        $table = new xmldb_table('quest_coderunner_options');
        $showSource = new xmldb_field('show_source', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, true, null, '0');
        $dbman->add_field($table, $showSource);
        upgrade_plugin_savepoint(true, 2013013101, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013102401) {
        // Add booleans to control display of result table columns
        $table = new xmldb_table('quest_coderunner_options');
        foreach (array('showtest', 'showstdin', 'showexpected', 'showoutput', 'showmark') as $newbool) {
            $default = $newbool === 'showmark' ? 0 : 1;
            $field = new xmldb_field($newbool, XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, true, null, $default);
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2013102401, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013102601) {
        $table = new xmldb_table('quest_coderunner_types');
        $validatorField = new xmldb_field('validator', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, false, null);
        $dbman->rename_field($table, $validatorField, 'grader');
        upgrade_plugin_savepoint(true, 2013102601, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013103102) {
        $table = new xmldb_table('quest_coderunner_testcases');
        $outputField = new xmldb_field('output', XMLDB_TYPE_TEXT, 'medium', XMLDB_UNSIGNED, false, null);
        $dbman->rename_field($table, $outputField, 'expected');
        upgrade_plugin_savepoint(true, 2013103102, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013110201) {
        $table = new xmldb_table('quest_coderunner_options');
        $templatedoesgrading = new xmldb_field('template_does_grading',
                XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, true, null, 0, 'custom_template');
        $dbman->add_field($table, $templatedoesgrading);
        upgrade_plugin_savepoint(true, 2013110201, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013110401) {
        // Add booleans to control display of result table columns
        $table = new xmldb_table('quest_coderunner_options');
        $timelimit = new xmldb_field('timelimitsecs', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, false, null, null);
        $dbman->add_field($table, $timelimit);
        $memlimit = new xmldb_field('memlimitmb', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, false, null, null);
        $dbman->add_field($table, $memlimit);
        upgrade_plugin_savepoint(true, 2013110401, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013110701) {
        $table = new xmldb_table('quest_coderunner_options');
        $timelimit = new xmldb_field('timelimitsecs', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, false, null, null);
        $dbman->rename_field($table, $timelimit, 'cputimelimitsecs');
        upgrade_plugin_savepoint(true, 2013110701, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013110702) {
        $table = new xmldb_table('quest_coderunner_types');
        $timelimit = new xmldb_field('cputimelimitsecs', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, false, null, null);
        $dbman->add_field($table, $timelimit);
        $memlimit = new xmldb_field('memlimitmb', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, false, null, null);
        $dbman->add_field($table, $memlimit);
        upgrade_plugin_savepoint(true, 2013110702, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013112101) {
        $table = new xmldb_table('quest_coderunner_options');
        $grader = new xmldb_field('grader', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, false, null, null);
        $dbman->add_field($table, $grader);
        upgrade_plugin_savepoint(true, 2013112101, 'qtype', 'coderunner');
    }


    if ($oldversion != 0 && $oldversion < 2013112102) {
        $DB->set_field('quest_coderunner_options', 'grader', 'TemplateGrader', array('template_does_grading' => 1));
        $table = new xmldb_table('quest_coderunner_options');
        $templatedoesgrading = new xmldb_field('template_does_grading');
        $dbman->drop_field($table, $templatedoesgrading);
        upgrade_plugin_savepoint(true, 2013112102, 'qtype', 'coderunner');
    }


    if ($oldversion != 0 && $oldversion < 2013112202) {
        $table = new xmldb_table('quest_coderunner_options');
        $customTemplate = new xmldb_field('custom_template', XMLDB_TYPE_TEXT, 'medium', XMLDB_UNSIGNED, false, null);
        $dbman->rename_field($table, $customTemplate, 'per_test_template');
        upgrade_plugin_savepoint(true, 2013112202, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013112203) {
        upgrade_plugin_savepoint(true, 2013112203, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013122508) {
        // Major change. Dispense with question_type table, using prototypal
        // inheritance within (extended) question table instead.

        update_to_use_prototypes();
        upgrade_plugin_savepoint(true, 2013122508, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2013123103) {

        // Define field enable_combinator to be added to quest_coderunner_options.
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('enable_combinator', XMLDB_TYPE_INTEGER, '1', null, false, null, null, 'test_splitter_re');

        // Conditionally launch add field enable_combinator.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Inherit combinator use on all except builtins and existing custom template questions
        $DB->set_field('quest_coderunner_options', 'enable_combinator', 0, array('prototype_type' => 0));
        $DB->set_field('quest_coderunner_options', 'enable_combinator', null, array('prototype_type' => 0, 'per_test_template' => null));
        $DB->set_field('quest_coderunner_options', 'enable_combinator', null, array('prototype_type' => 0, 'per_test_template' => ''));

        foreach (array('showtest', 'showstdin', 'showexpected', 'showoutput', 'showmark') as $fieldname) {
            $default = $fieldname === 'showmark' ? 0 : 1;
            $field = new xmldb_field($fieldname, XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, false, null, $default);
            $dbman->change_field_notnull($table, $field);  // Make it inheritable by making notnull false
        }

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2013123103, 'qtype', 'coderunner');

    }

    if ($oldversion != 0 && $oldversion < 2014021502) {
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('penalty_regime', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, false, null, null);
        // Conditionally launch add field enable_combinator.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2014021502, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2014022001) {

        // Add fields answerbox_lines and use_ace to coderunner_options

        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('answerbox_lines', XMLDB_TYPE_INTEGER, '5', null, XMLDB_NOTNULL, null, '18', 'show_source');

        // Conditionally launch add field answerbox_lines.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('use_ace', XMLDB_TYPE_INTEGER, '1', null, null, null, '1', 'answerbox_lines');

        // Conditionally launch add field use_ace.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2014022001, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2014022004) {

        // Define field answerbox_columns to be added to quest_coderunner_options.
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('answerbox_columns', XMLDB_TYPE_INTEGER, '5', null, null, null, '100', 'answerbox_lines');

        // Conditionally launch add field answerbox_columns.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2014022004, 'qtype', 'coderunner');
    }


    if ($oldversion != 0 && $oldversion < 2014022009) {
        // Fix screw up in version numbers resulting in broken DB upgrade
        update_to_use_prototypes();
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('enable_combinator', XMLDB_TYPE_INTEGER, '1', null, false, null, null, 'test_splitter_re');

        // Conditionally launch add field enable_combinator.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Inherit combinator use on all except builtins and existing custom template questions
        $DB->set_field('quest_coderunner_options', 'enable_combinator', 0, array('prototype_type' => 0));
        $DB->set_field('quest_coderunner_options', 'enable_combinator', null, array('prototype_type' => 0, 'per_test_template' => null));
        $DB->set_field('quest_coderunner_options', 'enable_combinator', null, array('prototype_type' => 0, 'per_test_template' => ''));

        foreach (array('showtest', 'showstdin', 'showexpected', 'showoutput', 'showmark') as $fieldname) {
            $default = $fieldname === 'showmark' ? 0 : 1;
            $field = new xmldb_field($fieldname, XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, false, null, $default);
            $dbman->change_field_notnull($table, $field);  // Make it inheritable by making notnull false
        }

        upgrade_plugin_savepoint(true, 2014022009, 'qtype', 'coderunner');
    }
    
    if ($oldversion != 0 && $oldversion < 2014042602) {
        // Delete all questions with prototype_type == 1
        // [Necessary because previous version didn't put prototypes into
        // a specific category, as required by the updateQuestionTypes method.]
        $prototypes = $DB->get_records('quest_coderunner_options',
                    array('prototype_type' => 1));
        foreach ($prototypes as $prototype) {
            $questionid = $prototype->questionid;
            $DB->delete_records('quest_coderunner_options',
                array('questionid' => $questionid));
            $DB->delete_records('question', array('id' => $questionid));
        }
    }
    
    if ($oldversion != 0 && $oldversion < 2014042704) {
        // Fix all non-inherited fields to be non null (except 
        // can't fix prototype_type as it's an index. No matter.)
        $table = new xmldb_table('quest_coderunner_options');
        $fields = array(
            new xmldb_field('penalty_regime', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, ''),
            // new xmldb_field('prototype_type', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, 0),
            new xmldb_field('answerbox_columns', XMLDB_TYPE_INTEGER, '5', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, 100),
            new xmldb_field('use_ace', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, 1));
        foreach ($fields as $field) {
            $dbman->change_field_type($table, $field);
        }
    }
    
    
    if ($oldversion != 0 && $oldversion < 2014052502) {

        // Define field sandbox_params to be added to quest_coderunner_options.
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('sandbox_params', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'memlimitmb');

        // Conditionally launch add field sandbox_params.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2014052502, 'qtype', 'coderunner');
    }
    
    
    if ($oldversion != 0 && $oldversion < 2014052801) {

        // Define field template_params to be added to quest_coderunner_options.
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('template_params', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'per_test_template');

        // Conditionally launch add field template_params.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2014052801, 'qtype', 'coderunner');
    }
    
    if ($oldversion != 0 && $oldversion < 2014060802) {
        // Define field sandbox_params to be added to quest_coderunner_options.
        // [Repeated as omitted from xml file so needed to be added in after
        // a clean install]
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('sandbox_params', XMLDB_TYPE_CHAR, '255', XMLDB_UNSIGNED, null, null, null);

        // Conditionally launch add field sandbox_params.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2014060802, 'qtype', 'coderunner');
    }
    
    
    if ($oldversion != 0 && $oldversion < 2014110301) {

        // Define field extra to be added to quest_coderunner_testcases.
        $table = new xmldb_table('quest_coderunner_testcases');
        $field = new xmldb_field('extra', XMLDB_TYPE_TEXT, null, null, null, null, null, 'expected');

        // Conditionally launch add field extra.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        // Define fields answer and ace-lang to be added to quest_coderunner_options.
        
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('answer', XMLDB_TYPE_TEXT, null, null, null, null, null, 'showoutput');

        // Conditionally launch add field answer
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        $field = new xmldb_field('ace_lang', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'language');

        // Conditionally launch add field ace_lang.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2014110301, 'qtype', 'coderunner');
    }
    
    if ($oldversion != 0 && $oldversion < 2014111002) {

        // Define field result_columns to be added to quest_coderunner_options.
        $table = new xmldb_table('quest_coderunner_options');
        $field = new xmldb_field('result_columns', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'showmark');
        // Conditionally launch add field result_columns.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        make_result_columns();

        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2014111002, 'qtype', 'coderunner');
    }

    if ($oldversion != 0 && $oldversion < 2015010401) {
        // Major changes for version 2.4. Rename options and testcases tables
        // plus all fields with underscores (removing the underscores).
        // Done for compatibility with Moodle coding guidelines. Sigh.
        $table1 = new xmldb_table('quest_coderunner_options');
        if ($dbman->table_exists($table1)) {
            $dbman->rename_table($table1, 'question_coderunner_options');
        }
        $table2 = new xmldb_table('quest_coderunner_testcases');
        if ($dbman->table_exists($table2)) {
            $dbman->rename_table($table2, 'question_coderunner_tests');
        }
        
        $optionstable = new xmldb_table('question_coderunner_options');
        
        // Now all the renames
        $oldfields = array(  // Fields requiring renaming (why do I need their types? Grrr.)
            array('coderunner_type', XMLDB_TYPE_CHAR, 255),
            array('prototype_type', XMLDB_TYPE_INTEGER, 1),
            array('all_or_nothing', XMLDB_TYPE_INTEGER, 1),
            array('answerbox_lines', XMLDB_TYPE_INTEGER, 5),
            array('answerbox_columns', XMLDB_TYPE_INTEGER, 5),
            array('use_ace', XMLDB_TYPE_INTEGER, 1),
            array('penalty_regime', XMLDB_TYPE_CHAR, 255),
            array('enable_combinator', XMLDB_TYPE_INTEGER, 1),
            array('result_columns', XMLDB_TYPE_CHAR, 255),
            array('combinator_template', XMLDB_TYPE_TEXT, 0),
            array('test_splitter_re', XMLDB_TYPE_CHAR, 255),
            array('per_test_template', XMLDB_TYPE_TEXT, 0),
            array('template_params', XMLDB_TYPE_CHAR, 255),
            array('ace_lang', XMLDB_TYPE_CHAR, 255),
            array('sandbox_params', XMLDB_TYPE_CHAR, 255),
            array('show_source', XMLDB_TYPE_INTEGER, 1)
        ); 
        foreach ($oldfields as $f) {
            list($fieldname, $type, $len) = $f;
            $newfieldname = str_replace('_', '', $fieldname);
            if ($len != 0) {
                $field = new xmldb_field($fieldname, $type, $len);
            } else {
                $field = new xmldb_field($fieldname, $type);
            }
            if ($dbman->field_exists($optionstable, $field)) {
                $dbman->rename_field($optionstable, $field, $newfieldname);
            }
        }
        
        // Coderunner savepoint reached.
        upgrade_plugin_savepoint(true, 2015010401, 'qtype', 'coderunner');

    }
    
    updateQuestionTypes();
            
    return true;

}


function update_to_use_prototypes() {
    global $CFG, $DB;
    $dbman = $DB->get_manager();

    $table = new xmldb_table('quest_coderunner_options');

    $field = new xmldb_field('prototype_type', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'coderunner_type');

    // Conditionally launch add field prototype_type.
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    $field = new xmldb_field('combinator_template', XMLDB_TYPE_TEXT, null, null, null, null, null, 'showmark');

    // Conditionally launch add field combinator_template.
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    $field = new xmldb_field('test_splitter_re', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'combinator_template');

    // Conditionally launch add field test_splitter_re.
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    $field = new xmldb_field('language', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'per_test_template');

    // Conditionally launch add field language.
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    $field = new xmldb_field('sandbox', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'language');

    // Conditionally launch add field sandbox.
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    $index = new xmldb_index('prototype_type', XMLDB_INDEX_NOTUNIQUE, array('prototype_type'));

    // Conditionally launch add index prototype_type.
    if (!$dbman->index_exists($table, $index)) {
        $dbman->add_index($table, $index);
    }


    $index = new xmldb_index('coderunner_type', XMLDB_INDEX_NOTUNIQUE, array('coderunner_type'));

    // Conditionally launch add index coderunner_type.
    if (!$dbman->index_exists($table, $index)) {
        $dbman->add_index($table, $index);
    }

    $table = new xmldb_table('quest_coderunner_types');
    // Conditionally launch drop table coderunner_types
    if ($dbman->table_exists($table)) {
        $dbman->drop_table($table);
    }
}


function updateQuestionTypes() {

    // Add/replace standard question types by deleting all questions in the
    // category CR_PROTOTYPES and reloading them from the file 
    // questions-CR_PROTOTYPES.xml.
    
    // Find id of CR_PROTOTYPES category
    global $DB, $CFG;
    
    $success = true;
    $systemcontext = context_system::instance();
    $systemcontextid = $systemcontext->id;
    if (!$systemcontextid) {
        $systemcontextid = 1; // HACK ALERT: occurs when phpunit initialising itself
    }
    $category = $DB->get_record('question_categories',
                array('contextid' => $systemcontextid, 'name' => 'CR_PROTOTYPES'));
    if ($category) { 
        $prototypecategoryid = $category->id;
    } else { // CR_PROTOTYPES category not defined yet. Add it
        $category = array(
            'name'      => 'CR_PROTOTYPES',
            'contextid' => $systemcontextid,
            'info'      => 'Category for CodeRunner question built-in prototypes. FOR SYSTEM USE ONLY.',
            'infoformat'=> 0,
            'parent'    => 0,
        );
        $prototypecategoryid = $DB->insert_record('question_categories', $category);
        if (!$prototypecategoryid) {
            throw new coding_exception("Upgrade failed: couldn't create CR_PROTOTYPES category");
        }
        $category = $DB->get_record('question_categories',
                array('id' => $prototypecategoryid));
    }
    
    // Delete all existing prototypes
    $prototypes = $DB->get_records_select('question',
            "category = $prototypecategoryid and name like '%PROTOTYPE_%'");
    foreach ($prototypes as $question) {
       $DB->delete_records('question_coderunner_options',
            array('questionid' => $question->id));
       $DB->delete_records('question', array('id' => $question->id));
    }
    
    $dbfiles = scandir(__DIR__);
    foreach ($dbfiles as $file) {
        // Load any files in the db directory ending with _PROTOTYPES.xml
        if (strpos(strrev($file), strrev('_PROTOTYPES.xml')) === 0) {
            $filename = __DIR__ . '/' . $file;
            load_questions($category, $filename, $systemcontextid);
        }
    }

    return $success;
}


function load_questions($category, $importfilename, $contextid) {
    // Load all the questions from the given import file into the given category
    // The category from the import file will be ignored if present.
    global $COURSE;
    $qformat = new qformat_xml();
    $qformat->setCategory($category);
    $systemcontext = context::instance_by_id($contextid);
    $contexts = new question_edit_contexts($systemcontext);
    $qformat->setContexts($contexts->having_one_edit_tab_cap('import'));
    $qformat->setCourse($COURSE);
    $qformat->setFilename($importfilename);
    $qformat->setRealfilename($importfilename);
    $qformat->setMatchgrades('error');
    $qformat->setCatfromfile(false);
    $qformat->setContextfromfile(false);
    $qformat->setStoponerror(true);

    // Do anything before that we need to
    if (!$qformat->importpreprocess()) {
        throw new coding_exception('Upgrade failed: error preprocessing prototype upload');
    }

    // Process the given file
    if (!$qformat->importprocess($category)) {
        throw new coding_exception('Upgrade failed: error uploading prototype questions');
    }

    // In case anything needs to be done after
    if (!$qformat->importpostprocess()) {
        throw new coding_exception('Upgrade failed: error postprocessing prototype upload');
    }
}


function make_result_columns() {
    // Find all questions using non-standard result table display and 
    // build a result_columns field that matches the currently defined 
    // set of showtest, showstdin, showexpected, showoutput and showmark
    // This code should only run prior to the version 2.4 upgrade.
    global $DB;
    
    $questions = $DB->get_records_select('quest_coderunner_options',
            "showtest != 1 or showstdin != 1 or showexpected != 1 or showoutput != 1 or showmark = 1");
    foreach ($questions as $q) {
        $cols = array();
        if ($q->showtest) {
            $cols[] = '["Test", "testcode"]';
        }
        if ($q->showstdin) {
            $cols[] = '["Input", "stdin"]';
        }
        if ($q->showexpected) {
            $cols[] = '["Expected", "expected"]';
        }
        if ($q->showoutput) {
            $cols[] = '["Got", "got"]';
        }
        if ($q->showmark) {
            $cols[] = '["Mark", "awarded", "mark", "%.2f/%.2f"]';
        }
        $field = '[' . implode(",", $cols) . ']';
        $row_id = $q->id;
        $query = "UPDATE {quest_coderunner_options} "
            . "SET result_columns = '$field' WHERE id=$row_id";
        $DB->execute($query);
    }
}


