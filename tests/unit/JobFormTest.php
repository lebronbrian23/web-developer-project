<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Validator;

class JobFormTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new Validator();
    }

    // Test cases for the Validator class to ensure it correctly validates job form data
    public function testValidatorAcceptsValidJobData()
    {
        $data = [
            'title' => 'Voice Actor Needed for Commercial',
            'script' => 'We are looking for a talented voice actor to bring our commercial script to life. The ideal candidate will have experience in voice acting and the ability to convey emotion and tone effectively.',
            'country' => 'CA',
            'state_or_province' => 'ON',
            'budget' => 'high'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertTrue($isValid);
    }

    // Additional test cases to cover various validation scenarios

    // Test that the validator rejects an empty title since it's required
    public function testValidatorRejectsEmptyTitle()
    {
        $data = [
            'title' => '',
            'script' => 'We are looking for a talented voice actor to bring our commercial script to life.',
            'country' => 'CA',
            'state_or_province' => 'ON',
            'budget' => 'medium'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);
    }

    // Test that the validator rejects a title that exceeds the maximum length
    public function testValidatorRejectsEmptyCountry()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => '',
            'state_or_province' => 'ON',
            'budget' => 'low'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);
    }

    // Test that the validator rejects an invalid budget value
    public function testValidatorRejectsInvalidBudget()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'US',
            'state_or_province' => 'CA',
            'budget' => 'not-a-valid-budget'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);
    }

    // Test that the validator rejects an invalid state/province for the selected country
    public function testValidatorRejectsInvalidStateForCountry()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'US',
            'state_or_province' => 'ON', // Ontario is not a valid state for US
            'budget' => 'medium'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);
    }

    // Test that the validator accepts an empty script since it's optional
    public function testValidatorAcceptsEmptyScript()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => '',
            'country' => 'CA',
            'state_or_province' => 'ON',
            'budget' => 'low'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertTrue($isValid);
    }

    // Test that the validator rejects a script that exceeds the maximum word count
    public function testValidatorRejectsScriptExceedingWordLimit()
    {
        $longScript = str_repeat('word ', 1001); // 1001 words 
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => $longScript,
            'country' => 'CA',
            'state_or_province' => 'ON',
            'budget' => 'medium'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);
    }

    // Test that the validator rejects a country that is not in the allowed list
    public function testValidatorRejectsInvalidCountry()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'UG', // Invalid country code
            'state_or_province' => 'ON',
            'budget' => 'high'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);

    }

    // Test that the validator rejects a state/province that is not valid for the selected country
    public function testValidatorRejectsInvalidStateForSelectedCountry()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'CA',
            'state_or_province' => 'CA', // California is not a valid province for Canada
            'budget' => 'medium'
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);

    }

    // Test that the validator rejects a budget value that is not in the allowed list
    public function testValidatorRejectsInvalidBudgetValue()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'US',
            'state_or_province' => 'CA',
            'budget' => 'extreme' // Invalid budget value
        ];

        $isValid = $this->validator->validate($data);
        $this->assertFalse($isValid);
    }

    // Test that the validator accepts a file with certain allowed extensions and within the size limit alongside valid job data
    public function testValidatorAcceptsValidFileUpload()
    {        
        $file = [
            'name' => 'reference.pdf',
            'type' => 'application/pdf',
            'tmp_name' => __DIR__ . '/test_files/reference.pdf', 
            'error' => UPLOAD_ERR_OK,
            'size' => 500000 // 500KB
        ];

        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'US',
            'state_or_province' => 'CA',
            'budget' => 'medium',
            'reference_file_path' => $file['name']
        ];

        $isValid = $this->validator->validate($data);
        $this->assertTrue($isValid);
    }

    // Test that the validator rejects a file upload that exceeds the maximum size limit
    public function testValidatorRejectsFileExceedingSizeLimit()
    {

        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'US',
            'state_or_province' => 'CA',
            'budget' => 'medium'
        ];

        $files = [
            'reference_file_path' => [
                'name' => 'large_file.pdf',
                'type' => 'application/pdf',
                'tmp_name' => __DIR__ . '/test_files/large_file.pdf',
                'error' => UPLOAD_ERR_OK,
                'size' => 25 * 1024 * 1024 // 25MB, max is 20MB
            ]
        ];

        $isValid = $this->validator->validate($data, $files);
        $this->assertFalse($isValid);
    }

    // Test that the validator rejects a file upload with an invalid file type
    public function testValidatorRejectsInvalidFileType()
    {
        $data = [
            'title' => 'Voice Actor Needed',
            'script' => 'We are looking for a talented voice actor.',
            'country' => 'US',
            'state_or_province' => 'CA',
            'budget' => 'medium'
        ];

        $files = [
            'reference_file_path' => [
                'name' => 'malicious_script.exe',
                'type' => 'application/x-msdownload',
                'tmp_name' => __DIR__ . '/test_files/malicious_script.exe',
                'error' => UPLOAD_ERR_OK,
                'size' => 500000 
            ]
        ];

        $isValid = $this->validator->validate($data, $files);
        $this->assertFalse($isValid);
    }



}