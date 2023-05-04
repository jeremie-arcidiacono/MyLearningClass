<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    February 2023
 * Description :    This test class tests the Validator rules.
 ** * * * * * * * * * * * * * * * * * * * * * * */

namespace Tests\Unit;

use App\Models\User;
use App\Validator;
use PHPUnit\Framework\TestCase;
use Tests\HasFakeDB;

/**
 * Test the Validator class.
 * @uses \App\Validator
 * @uses \Tests\HasFakeDB
 */
class ValidatorTest extends TestCase
{
    use HasFakeDB;

    public function test_validator_without_providing_entityManager(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('One of the validation rules requires an EntityManager, but none was provided when creating the Validator object');
        new Validator(['userId' => 1], ['userId' => ['exists:User']]);
    }

    public function test_validator_with_unknow_rule(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The rule unknowRule does not exist');
        new Validator(['userId' => 1], ['userId' => ['unknowRule']]);
    }



    //<editor-fold desc="Test of generic rules">

    /** @dataProvider ruleRequiredCases */
    public function test_rule_required(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['required']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleRequiredCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['test', true],
            'empty array' => [[], false],
            'array' => [['test'], true],
            'int 0' => [0, true],
            'int 1' => [1, true],
            'float 0' => [0.0, true],
            'float 1' => [1.0, true],
            'bool false' => [false, true],
            'bool true' => [true, true],
        ];
    }

    /** @dataProvider ruleEmailCases */
    public function test_rule_email(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['email']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleEmailCases(): array
    {
        return [
            'empty string' => ['', false],
            'fake email 1' => ['test', false],
            'fake email 2' => ['test@', false],
            'fake email 3' => ['test@test', false],
            'fake email 4' => ['test@test.', false],
            'fake email 5' => ['test@@test.com', false],
            'fake email 6' => ['test@test..com', false],
            'fake email 7' => ['@test.com', false],
            'fake email 8' => ['test@.com', false],
            'true email 1' => ['test@test.com', true]
        ];
    }


    /** @dataProvider ruleSameCases */
    public function test_rule_same(mixed $value, mixed $value2, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value, 'myTestField2' => $value2], ['myTestField' => ['same:myTestField2']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleSameCases(): array
    {
        return [
            'empty string' => ['', '', true],
            'string' => ['test', 'test', true],
            'empty array' => [[], [], true],
            'array' => [['test'], ['test'], true],
            'int 0' => [0, 0, true],
            'int 1' => [1, 1, true],
            'float 0' => [0.0, 0.0, true],
            'float 1' => [1.0, 1.0, true],
            'bool false' => [false, false, true],
            'bool true' => [true, true, true],
            'different string' => ['test', 'test2', false],
            'different type' => [1, true, false],
        ];
    }

    public function test_rule_enum_classic(): void
    {
        $validator = new Validator(['myTestField' => 'test'], ['myTestField' => ['enum:' . TestEnum::class]]);
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['myTestField' => 'test3'], ['myTestField' => ['enum:' . TestEnum::class]]);
        $this->assertFalse($validator->isValid());
    }

    public function test_rule_enum_backed(): void
    {
        $validator = new Validator(['myTestField' => '1'], ['myTestField' => ['enum:' . TestBackedEnum::class]]);
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['myTestField' => '3'], ['myTestField' => ['enum:' . TestBackedEnum::class]]);
        $this->assertFalse($validator->isValid());
    }

    public function test_rule_enum_non_existant(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Enum called NonExistantEnum does not exist');
        new Validator(['myTestField' => 'test'], ['myTestField' => ['enum:' . 'NonExistantEnum']]);
    }
    //</editor-fold>


    //<editor-fold desc="Test of text rules">

    /** @dataProvider ruleStringCases */
    public function test_rule_string(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['string']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleStringCases(): array
    {
        return [
            'empty string' => ['', true],
            'string' => ['test', true],
            'empty array' => [[], false],
            'array' => [['test'], false],
            'int 0' => [0, false],
            'int 1' => [1, false],
            'float 0' => [0.0, false],
            'float 1' => [1.0, false],
            'bool false' => [false, false],
            'bool true' => [true, false],
        ];
    }

    /** @dataProvider ruleLenminCases */
    public function test_rule_lenmin(mixed $value, int $min, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['lenmin:' . $min]]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleLenminCases(): array
    {
        return [
            'empty string' => ['', 1, false],
            'string min 2' => ['test', 2, true],
            'string min 3' => ['test', 3, true],
            'string min 4' => ['test', 4, true],
            'string min 5' => ['test', 5, false],
        ];
    }


    /** @dataProvider ruleLenmaxCases */
    public function test_rule_lenmax(mixed $value, int $max, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['lenmax:' . $max]]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleLenmaxCases(): array
    {
        return [
            'empty string' => ['', 2, true],
            'string max 2' => ['test', 2, false],
            'string max 3' => ['test', 3, false],
            'string max 4' => ['test', 4, true],
            'string max 5' => ['test', 5, true],
        ];
    }

    /** @dataProvider ruleLenCases */
    public function test_rule_len(mixed $value, int $len, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['len:' . $len]]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleLenCases(): array
    {
        return [
            'empty string' => ['', 1, false],
            'string len 2' => ['test', 2, false],
            'string len 3' => ['test', 3, false],
            'string len 4' => ['test', 4, true],
            'string len 5' => ['test', 5, false],
            'numeric len 1' => [123, 2, false],
            'numeric len 2' => [123, 3, true],
            'numeric len 3' => [123, 4, false],
        ];
    }

    /** @dataProvider ruleContainUpperCases */
    public function test_rule_contain_upper(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['containUpper']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleContainUpperCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['test', false],
            'string with upper' => ['T', true],
            'string with multiple upper' => ['TEST', true],
            'string with upper and number' => ['Test123', true],
            'string with upper and number and special' => ['Test 123 !', true],
            'string with lower and number and special' => ['test 123 !', false],
            'string with number and special' => ['123 !', false],
            'string with special' => ['!', false],
        ];
    }

    /** @dataProvider ruleContainLowerCases */
    public function test_rule_contain_lower(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['containLower']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleContainLowerCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['TEST', false],
            'string with lower' => ['t', true],
            'string with multiple lower' => ['test', true],
            'string with lower and number' => ['Test123', true],
            'string with lower and number and special' => ['Test 123 !', true],
            'string with upper and number and special' => ['TEST 123 !', false],
            'string with number and special' => ['123 !', false],
            'string with special' => ['!', false],
        ];
    }

    /** @dataProvider ruleContainNumberCases */
    public function test_rule_contain_number(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['containNumber']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleContainNumberCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['TEST', false],
            'string with number' => ['1', true],
            'string with multiple number' => ['123', true],
            'string with lower and number' => ['Test123', true],
            'string with lower and number and special' => ['Test 123 !', true],
            'string with upper and number and special' => ['TEST 123 !', true],
            'string with special' => ['!', false],
        ];
    }

    /** @dataProvider ruleContainSpecialCases */
    public function test_rule_contain_special(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['containSpecial']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    /**
     * @see https://owasp.org/www-community/password-special-characters
     */
    public static function ruleContainSpecialCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['TEST', false],
            'string with special' => ['!', true],
            'string with multiple special' => ['!@#', true],
            'string with lower and special' => ['test!', true],
            'string with lower and number and special' => ['test 123 !', true],
            'string with upper and number and special' => ['TEST 123 !', true],
            'string with number and special' => ['123 !', true],
            'space' => [' ', true],
            'exclamation mark' => ['!', true],
            'double quote' => ['"', true],
            'hash' => ['#', true],
            'dollar' => ['$', true],
            'percent' => ['%', true],
            'ampersand' => ['&', true],
            'single quote' => ["'", true],
            'left parenthesis' => ['(', true],
            'right parenthesis' => [')', true],
            'asterisk' => ['*', true],
            'plus' => ['+', true],
            'comma' => [',', true],
            'minus' => ['-', true],
            'dot' => ['.', true],
            'slash' => ['/', true],
            'colon' => [':', true],
            'semicolon' => [';', true],
            'less than' => ['<', true],
            'equal' => ['=', true],
            'greater than' => ['>', true],
            'question mark' => ['?', true],
            'at' => ['@', true],
            'left bracket' => ['[', true],
            'backslash' => ['\\', true],
            'right bracket' => [']', true],
            'caret' => ['^', true],
            'underscore' => ['_', true],
            'backtick' => ['`', true],
            'left brace' => ['{', true],
            'vertical bar' => ['|', true],
            'right brace' => ['}', true],
            'tilde' => ['~', true],
        ];
    }
    //</editor-fold>


    //<editor-fold desc="Test of numeric rules">

    /** @dataProvider ruleNumericCases */
    public function test_rule_numeric(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['numeric']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleNumericCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['test', false],
            'empty array' => [[], false],
            'array' => [['test'], false],
            'int 0' => [0, true],
            'int 1' => [1, true],
            'float 0' => [0.0, true],
            'float 1' => [1.0, true],
            'bool false' => [false, false],
            'bool true' => [true, false],
        ];
    }

    /** @dataProvider ruleIntegerCases */
    public function test_rule_integer(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['integer']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleIntegerCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['test', false],
            'empty array' => [[], false],
            'array' => [['test'], false],
            'int 0' => [0, true],
            'int 1' => [1, true],
            'float 0' => [0.0, true],
            'float 1' => [1.0, true],
            'float 1.001' => [1.001, false],
            'bool false' => [false, false],
            'bool true' => [true, false],
        ];
    }

    /** @dataProvider ruleMinCases */
    public function test_rule_min(mixed $value, int $min, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['min:' . $min]]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleMinCases(): array
    {
        return [
            'number 1' => [5, 4, true],
            'number 2' => [5, 5, true],
            'number 3' => [5, 6, false],
        ];
    }

    /** @dataProvider ruleMaxCases */
    public function test_rule_max(mixed $value, int $max, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['max:' . $max]]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleMaxCases(): array
    {
        return [
            'number 1' => [5, 4, false],
            'number 2' => [5, 5, true],
            'number 3' => [5, 6, true],
        ];
    }
    //</editor-fold>


    //<editor-fold desc="Test of date rules">

    /** @dataProvider ruleDateCases */
    public function test_rule_date(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['date']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleDateCases(): array
    {
        return [
            'empty string' => ['', false],
            'string' => ['test', false],
            'empty array' => [[], false],
            'array' => [['test'], false],
            'int' => [1, false],
            'malformed date' => ['2021-132-01', false],
            'date 1' => ['2021-12-01', true],
            'date 2' => ['01-12-0021', true],
            'date 3' => ['01-12-21', true],
            'date 4' => ['1-2-21', true],
        ];
    }

    public function test_rule_field_before_field(): void
    {
        $validator = new Validator(['date1' => '06-07-2022', 'date2' => '26-07-2022'], ['date1' => ['beforeField:date2']]);
        $this->assertTrue($validator->isValid());
    }

    public function test_rule_field_not_before_field(): void
    {
        $validator = new Validator(['date1' => '30-07-2022', 'date2' => '26-07-2022'], ['date1' => ['beforeField:date2']]);
        $this->assertFalse($validator->isValid());
    }

    public function test_rule_field_before_or_equals_field(): void
    {
        $validator = new Validator(['date1' => '26-07-2022', 'date2' => '26-07-2022'], ['date1' => ['beforeField:date2']]);
        $this->assertTrue($validator->isValid());
    }

    public function test_rule_field_after_field(): void
    {
        $validator = new Validator(['date1' => '26-07-2022', 'date2' => '06-07-2022'], ['date1' => ['afterField:date2']]);
        $this->assertTrue($validator->isValid());
    }

    public function test_rule_field_not_after_field(): void
    {
        $validator = new Validator(['date1' => '26-07-2022', 'date2' => '30-07-2022'], ['date1' => ['afterField:date2']]);
        $this->assertFalse($validator->isValid());
    }

    public function test_rule_field_after_or_equals_field(): void
    {
        $validator = new Validator(['date1' => '26-07-2022', 'date2' => '26-07-2022'], ['date1' => ['afterField:date2']]);
        $this->assertTrue($validator->isValid());
    }

    /** @dataProvider ruleAfterCases */
    public function test_rule_after(mixed $value, string $dateToCompare, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['after:' . $dateToCompare]]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleAfterCases(): array
    {
        return [
            'date 1' => ['2021-01-01', '2021-01-01', true],
            'date 2' => ['2021-01-01', '2021-01-02', false],
            'date 3' => ['2021-01-02', '2021-01-01', true],
            'now' => ['3000-01-01', 'now', true],
            'now implicit' => ['3000-01-01', '', true],
        ];
    }

    /** @dataProvider ruleBeforeCases */
    public function test_rule_before(mixed $value, string $dateToCompare, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['before:' . $dateToCompare]]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleBeforeCases(): array
    {
        return [
            'date 1' => ['2021-01-01', '2021-01-01', true],
            'date 2' => ['2021-01-01', '2021-01-02', true],
            'date 3' => ['2021-01-02', '2021-01-01', false],
            'now' => ['2000-01-01', 'now', true],
            'now implicit' => ['2000-01-01', '', true],
        ];
    }
    //</editor-fold>


    //<editor-fold desc="Test of time rules">

    /** @dataProvider ruleTimeCases */
    public function test_rule_time(mixed $value, bool $exceptedValid): void
    {
        $validator = new Validator(['myTestField' => $value], ['myTestField' => ['time']]);
        $this->assertSame($validator->isValid(), $exceptedValid);
    }

    public static function ruleTimeCases(): array
    {
        return [
            'string' => ['test', false],
            'empty array' => [[], false],
            'array' => [['test'], false],
            'int' => [1, false],
            'malformed time' => ['23:64:00', false],
            'time 1' => ['12:00:00', true],
            'time 2' => ['12:00', true],
            'time 3' => ['12:00:00:00', false],
        ];
    }
    //</editor-fold>


    //<editor-fold desc="Test of database rules">

    public function test_rule_exists_1(): void
    {
        // Check user's email field when it exists
        $this->setUpFakeDb();

        $user = new User();
        $user->setEmail('mail@gmail.com')
            ->setPassword('password')
            ->setFirstname('firstName')
            ->setLastname('lastName');

        $this->db->persist($user);
        $this->db->flush();

        $validator = new Validator(['myTestField' => 'mail@gmail.com'], ['myTestField' => ['exists:\App\Models\User:email']], $this->db);
        $this->assertTrue($validator->isValid());
    }

    public function test_rule_exists_2(): void
    {
        // Check event's id field when it does not exist
        $this->setUpFakeDb();

        $validator = new Validator(['myTestField' => '9999'], ['myTestField' => ['exists:\App\Models\Event']], $this->db);
        $this->assertFalse($validator->isValid());
    }

    public function test_rule_exists_3(): void
    {
        // Check user's id field when id is set
        $this->setUpFakeDb();

        $user = new User();
        $user->setEmail('mail@gmail.com')
            ->setPassword('password')
            ->setFirstname('firstName')
            ->setLastname('lastName');

        $this->db->persist($user);
        $this->db->flush();

        $validator = new Validator(['myTestField' => '1'], ['myTestField' => ['exists:\App\Models\User']], $this->db);
        $this->assertTrue($validator->isValid());
    }

    public function test_rule_unique_1(): void
    {
        // Check user's firstname field when it does not exist
        $this->setUpFakeDb();

        $validator = new Validator(['myTestField' => 'nonExistingName'], ['myTestField' => ['unique:\App\Models\User:firstname']],
            $this->db);
        $this->assertTrue($validator->isValid());
    }

    public function test_rule_unique_2(): void
    {
        // Check user's email field when it exists
        $this->setUpFakeDb();

        $user = new User();
        $user->setEmail('mail@gmail.com')
            ->setPassword('password')
            ->setFirstname('firstName')
            ->setLastname('lastName');

        $this->db->persist($user);
        $this->db->flush();

        $validator = new Validator(['myTestField' => 'mail@gmail.com'], ['myTestField' => ['unique:\App\Models\User:email']], $this->db);
        $this->assertFalse($validator->isValid());
    }

    public function test_rule_unique_3(): void
    {
        // Check user's email field when it exists but is ignored
        $this->setUpFakeDb();

        $user = new User();
        $user->setEmail('mail@gmail.com')
            ->setPassword('password')
            ->setFirstname('firstName')
            ->setLastname('lastName');

        $this->db->persist($user);
        $this->db->flush();

        $validator = new Validator(['myTestField' => 'mail@gmail.com'], ['myTestField' => ['unique:\App\Models\User:email:1']], $this->db);
        $this->assertTrue($validator->isValid());
    }

    public function test_rule_unique_4(): void
    {
        // Check reservation's id field when it does not exist
        $this->setUpFakeDb();

        $validator = new Validator(['myTestField' => '9999'], ['myTestField' => ['unique:\App\Models\Reservation']], $this->db);
        $this->assertTrue($validator->isValid());
    }
    //</editor-fold>


    // Other tests

    /** @dataProvider rulesWithNullCases */
    public function test_rules_with_null(string $ruleName, bool $excepted): void
    {
        $this->setUpFakeDb();

        $validator = new Validator([
            'myTestField' => null,
            'otherNullField' => null,
            'otherField' => 'notNull',
        ], ['myTestField' => [$ruleName]], $this->db);
        $this->assertSame($validator->isValid(), $excepted);
    }

    public static function rulesWithNullCases(): array
    {
        return [
            'required' => ['required', false],
            'email' => ['email', true],
            'same 1' => ['same:otherField', true],
            'same 2' => ['same:otherNullField', true],
            'string' => ['string', true],
            'lenmin 1' => ['lenmin:5', true],
            'lenmin 2' => ['lenmin:0', true],
            'lenmax 1' => ['lenmax:5', true],
            'lenmax 2' => ['lenmax:0', true],
            'len 1' => ['len:5', true],
            'len 2' => ['len:0', true],
            'numeric' => ['numeric', true],
            'integer' => ['integer', true],
            'min 1' => ['min:5', true],
            'min 2' => ['min:0', true],
            'max 1' => ['max:5', true],
            'max 2' => ['max:0', true],
            'date' => ['date', true],
            'time' => ['time', true],
            'after' => ['after:2021-01-01', true],
            'before' => ['before:2021-01-01', true],
            'exists' => ['exists:\App\Models\User', true],
            'unique' => ['unique:\App\Models\User', true],
        ];
    }

    /** @dataProvider rulesWithEmptyStringCases */
    public function test_rules_with_empty_string(string $ruleName, bool $excepted): void
    {
        $this->setUpFakeDb();

        $validator = new Validator([
            'myTestField' => '',
            'otherNullField' => null,
            'otherField' => 'notNull',
        ], ['myTestField' => [$ruleName]], $this->db);
        $this->assertSame($validator->isValid(), $excepted);
    }

    public static function rulesWithEmptyStringCases(): array
    {
        return [
            'required' => ['required', false],
            'email' => ['email', false],
            'same 1' => ['same:otherField', false],
            'same 2' => ['same:otherNullField', false],
            'string' => ['string', true],
            'lenmin 1' => ['lenmin:5', false],
            'lenmin 2' => ['lenmin:0', true],
            'lenmax 1' => ['lenmax:5', true],
            'lenmax 2' => ['lenmax:0', true],
            'len 1' => ['len:5', false],
            'len 2' => ['len:0', true],
            'numeric' => ['numeric', false],
            'integer' => ['integer', false],
            'date' => ['date', false],
            'time' => ['time', false],
            'exists' => ['exists:\App\Models\User', false],
            'unique' => ['unique:\App\Models\User', true],
        ];
    }
}

enum TestEnum
{
    case test;
    case test2;
}

enum TestBackedEnum: string
{
    case test = '1';
    case test2 = '2';
}
