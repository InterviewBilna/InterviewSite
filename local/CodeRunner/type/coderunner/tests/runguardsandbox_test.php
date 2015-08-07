<?php
/**
 * Unit tests for coderunner's RunguardSandbox sandbox class.
 * @group qtype_coderunner
 * This is just a copy of the LiuSandbox test class, with as few
 * changes as possible. [Yeah, Horrible, Horrible]
 *
 * @package    qtype
 * @subpackage coderunner
 * @copyright  2012 Richard Lobb, University of Canterbury
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG;
require_once($CFG->dirroot . '/question/type/coderunner/tests/coderunnertestcase.php');
require_once($CFG->dirroot . '/question/type/coderunner/sandbox/runguardsandbox.php');

class qtype_coderunner_runguardsandbox_test extends qtype_coderunner_testcase {

    public function test_testfunction() {
        $this->check_sandbox_enabled('runguardsandbox');
        $sandbox = new qtype_coderunner_runguardsandbox();
        $tr = $sandbox->test_function();
        $this->assertEquals(qtype_coderunner_sandbox::OK, $tr->error);
        $this->assertEquals(3.14, $tr->pi);
        $this->assertEquals(42, $tr->answerToLifeAndEverything);
        $this->assertTrue($tr->oOok);
        $langs = $sandbox->get_languages()->languages;
        $this->assertTrue(in_array('python2', $langs, true));
        $this->assertTrue(in_array('matlab', $langs, true));
        $this->assertTrue(in_array('java', $langs, true));
    }


    // Test the runguardsandbox class at the PHP level with a good Python2 program
    public function test_runguardsandbox_ok_python2() {
        $this->check_sandbox_enabled('runguardsandbox');
        $sandbox = new qtype_coderunner_runguardsandbox();
        $code = "print 'Hello Sandbox'\nprint 'Python rulz'";
        $result = $sandbox->execute($code, 'python2', null);
        $this->assertEquals(qtype_coderunner_sandbox::OK, $result->error);
        $this->assertEquals(qtype_coderunner_sandbox::RESULT_SUCCESS, $result->result);
        $this->assertEquals("Hello Sandbox\nPython rulz\n", $result->output);
        $this->assertEquals(0, $result->signal);
        $this->assertEquals('', $result->cmpinfo);
        $sandbox->close();
    }

    // Test the runguardsandbox class at the PHP level with a bad-syntax python2 prog
    // Syntax checking is not currently implemented by the runguardsandbox, so the
    // program 'runs' but terminates abnormally with a syntax error.
    public function test_runguardsandbox_syntax_error_python2() {
        $this->check_sandbox_enabled('runguardsandbox');
        $sandbox = new qtype_coderunner_runguardsandbox();
        $code = "print 'Hello Sandbox'\nprint 'Python rulz' + ";
        $result = $sandbox->execute($code, 'python2', null);
        $this->assertEquals(qtype_coderunner_sandbox::OK, $result->error);
        $this->assertEquals(qtype_coderunner_sandbox::RESULT_ABNORMAL_TERMINATION, $result->result);
        $this->assertEquals(0, $result->signal);
        $this->assertTrue(strpos($result->stderr, 'SyntaxError') !== false);
        $sandbox->close();
    }

    
    // Test the runguardsandbox with a timeout error. On runguardsandbox this gives
    // signal 9.
    public function test_runguardsandbox_timeout() {
        $this->check_sandbox_enabled('runguardsandbox');
        $sandbox = new qtype_coderunner_runguardsandbox();
        $code = "while True: pass";
        $result = $sandbox->execute($code, 'python2', null);
        $this->assertEquals(qtype_coderunner_sandbox::OK, $result->error);
        $this->assertEquals(qtype_coderunner_sandbox::RESULT_TIME_LIMIT, $result->result);
        $this->assertEquals('', $result->output);
        $this->assertTrue($result->signal == 9);
        $this->assertEquals('', $result->cmpinfo);
        $sandbox->close();
    }

    // Test the runguardsandbox with a memory limit error
    public function test_runguardsandbox_memlimit() {
        $this->check_sandbox_enabled('runguardsandbox');
        $sandbox = new qtype_coderunner_runguardsandbox();
        $code = "data = list(range(1,100000000000))";
        $result = $sandbox->execute($code, 'python2', null);
        $this->assertEquals(qtype_coderunner_sandbox::OK, $result->error);
        $this->assertEquals(qtype_coderunner_sandbox::RESULT_ABNORMAL_TERMINATION, $result->result);
        $this->assertEquals('', $result->output);
        $this->assertTrue(strpos($result->stderr, 'MemoryError') !== false ||
                strpos($result->stderr, 'OverflowError') !== false);
        $this->assertEquals('', $result->cmpinfo);
        $sandbox->close();
    }


    // Test the runguardsandbox with excessive output, using Python3
    // Actually generates a time limit error, because of the limitations
    // of runguard.
    public function test_runguardsandbox_excessiveoutput() {
        $this->check_sandbox_enabled('runguardsandbox');
        $sandbox = new qtype_coderunner_runguardsandbox();
        $code = "while 1: print('blah blah blah blah blah blah blah')";
        $result = $sandbox->execute($code, 'python3', null);
        $this->assertEquals(qtype_coderunner_sandbox::OK, $result->error);
        $this->assertEquals(qtype_coderunner_sandbox::RESULT_TIME_LIMIT, $result->result);
        $this->assertTrue($result->signal == 9);
        $this->assertEquals('', $result->cmpinfo);
        $sandbox->close();
    }
}


