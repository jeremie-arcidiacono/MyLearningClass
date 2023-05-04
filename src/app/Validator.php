<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    November 2022
 * Description :    This class is used to validate array of data with rules. (For example, the data sent by a form)
 *                  It is inspired by the Laravel Validator class.
 *
 *                  To add a new rule, you must add a method here named "rule_" + the name of the rule (e.g. rule_required).
 ** * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use BackedEnum;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

/**
 * Used to validate array of data with rules.
 */
class Validator
{
    //<editor-fold desc="All logics (contains all things that are not rules)">
    /**
     * The format used to display a date in an error message
     */
    private const DATE_FORMAT_IN_ERROR = 'd-m-Y';
    private array $errors;

    /**
     * @param array $data            An associative array with the data to validate (e.g. ['username' => 'John', 'password' => '1234']). Note that the data will be unescaped.
     * @param array $rules           An associative array with the rules to apply to the data (e.g. ['username' => 'required', 'password' => 'required|min:8'])
     * @param EntityManager|null $em Optional. Must be provided if one of the rules requires a connection to the database (e.g. 'unique', 'exists', etc.)
     * @throws \Exception If a rule does not exist
     */
    public function __construct(private array $data, private readonly array $rules, private readonly EntityManager|null $em = null)
    {
        // Make sure the data inside the array is not escaped
        $this->data = array_map(
            function ($value) {
                if (is_string($value)) {
                    return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
                }
                return $value;
            }
            , $this->data
        );

        $this->errors = [];
        $this->processRules();
    }

    /**
     * Check if the Validator object has a connection to the database.
     * This method is called before running a validation rule that requires a connection to the database.
     * @return void
     * @throws \Exception When the Validator object does not have a connection to the database
     */
    private function checkEntityManager(): void
    {
        if ($this->em === null) {
            throw new \Exception(
                'One of the validation rules requires an EntityManager, but none was provided when creating the Validator object'
            );
        }
    }

    /**
     * Return true if all the rules are respected
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }


    /**
     * Get all errors for all fields
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get errors for all fields, but only the first error for each field
     * @return array
     */
    public function getOnlyFirstErrors(): array
    {
        $errors = $this->getErrors();
        return array_map(fn($error) => $error[0], $errors);
    }

    /**
     * Get all errors for a specific field
     * @param string $field
     * @return array|null
     */
    public function getErrorsForField(string $field): ?array
    {
        return $this->errors[$field] ?? null;
    }


    /**
     * Run the validation rules for all fields
     * @return void
     * @throws \Exception If a rule does not exist
     */
    private function processRules(): void
    {
        // Clean the error array for the field
        $this->errors = [];

        foreach ($this->rules as $field => $rules) {
            // Check if the value is null
            if (!isset($this->data[$field])) {
                // Check if there is rule 'required' for this field
                $requiredRule = array_filter($rules, fn($rule) => explode(':', $rule)[0] === 'required');
                if (!empty($requiredRule)) {
                    // This field does not respect the 'required' rule, so we add an error and don't check other rules
                    $this->processRule($field, 'required');
                }
                continue;
            }
            foreach ($rules as $rule) {
                $this->processRule($field, $rule);
            }
        }
    }

    /**
     * Run a validation rule for a specific field
     * @param string $field The name of the field
     * @param string $rule  The name of the rule
     * @return void
     * @throws \Exception If the rule does not exist
     */
    private function processRule(string $field, string $rule): void
    {
        $rule = explode(':', $rule);
        $ruleName = $rule[0];
        $ruleParams = array_slice($rule, 1);

        $ruleParams = array_map(fn($param) => $param === 'null' ? null : $param,
            $ruleParams); // If the param is null in string format, convert it to null

        $ruleMethod = 'rule_' . $ruleName;
        if (method_exists($this, $ruleMethod)) {
            $this->$ruleMethod($field, ...$ruleParams);
        }
        else {
            throw new \Exception("The rule $ruleName does not exist");
        }
    }

    /**
     * Helpers
     * Convert a field name to a user-friendly name
     * @param string $value Ex: 'my_field' or 'anotherExample'
     * @return string Ex: 'My field' or 'Another example'
     */
    private function humanFriendly(string $value): string
    {
        $str = str_replace('_', ' ', $value);
        $str = trim(preg_replace('/(?<! )[A-Z]/', ' $0', $str)); // Put a space before each capital letter
        return ucfirst(strtolower($str));
    }
    //</editor-fold>

    // Begin of all rules methods

    // Special rules
    //<editor-fold desc="Special rules region">


    //</editor-fold>


    // Generic rules
    //<editor-fold desc="Generic rules region">

    /**
     * The field must NOT be :
     *  - null
     *  - an empty string
     *  - an empty array
     *
     * USAGE : 'required'
     * @param string $field
     * @return bool
     */
    private function rule_required(string $field): bool
    {
        if (!isset($this->data[$field])
            || $this->data[$field] === ''
            || $this->data[$field] === null
            || $this->data[$field] === []) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} ne peut pas être vide";
            return false;
        }
        return true;
    }

    /**
     * The field must be an email (respect FILTER_VALIDATE_EMAIL (RFC 822))
     *
     * USAGE : 'email'
     * @param string $field
     * @return bool
     */
    private function rule_email(string $field): bool
    {
        if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une adresse email valide";
            return false;
        }
        return true;
    }

    /**
     * The field must be the same as another field (strict comparison)
     *
     * USAGE : 'same:other_field_name'
     * @param string $field      The field to check
     * @param string $otherField The field to compare with
     * @return bool
     */
    private function rule_same(string $field, string $otherField): bool
    {
        if ($this->data[$field] !== $this->data[$otherField]) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être égal au champs {$this->humanFriendly($otherField)}";
            return false;
        }
        return true;
    }

    /**
     * The field must be a value in an enum
     *
     * USAGE : 'enum:' . MyEnum::class
     * @param string $field
     * @param string $enumName The name of the enum (ex: MyEnum::class)
     * @return bool
     * @throws \Exception If the enum does not exist
     */
    private function rule_enum(string $field, string $enumName): bool
    {
        if (enum_exists($enumName)) {
            // If it's a backed enum
            if (is_subclass_of($enumName, BackedEnum::class)) {
                if ($enumName::tryFrom($this->data[$field]) === null) {
                    $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une valeur valide";
                    return false;
                }
            }
            else { // If it's a pure enum
                try {
                    $enum = constant($enumName . '::' . $this->data[$field]);
                    if ($enum === null) {
                        $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une valeur valide";
                        return false;
                    }
                } catch (\Error|\Exception $e) {
                    $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une valeur valide";
                    return false;
                }
            }
        }
        else {
            throw new \Exception("Enum called $enumName does not exist");
        }
        return true;
    }
    //</editor-fold>


    // Text rules
    //<editor-fold desc="Text rules region">

    /**
     * The field must be a string (respect is_string())
     *
     * USAGE : 'string'
     * @param string $field
     * @return bool
     */
    private function rule_string(string $field): bool
    {
        if (!is_string($this->data[$field])) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une chaîne de caractères";
            return false;
        }
        return true;
    }

    /**
     * The field must be at least $min characters/digits long (works with strings or numbers)
     *
     * USAGE : 'lenmin:5'
     * @param string $field
     * @param string|int $min The minimum length
     * @return bool
     */
    private function rule_lenmin(string $field, string|int $min): bool
    {
        $data = (string)$this->data[$field];
        if (strlen($data ?? '') < $min) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit contenir au moins $min caractères";
            return false;
        }
        return true;
    }

    /**
     * The field must be at most $max characters/digits long (works with strings or numbers)
     *
     * USAGE : 'lenmax:5'
     * @param string $field
     * @param string|int $max The maximum length
     * @return bool
     */
    private function rule_lenmax(string $field, string|int $max): bool
    {
        $data = (string)$this->data[$field];
        if (strlen($data ?? '') > $max) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} ne doit pas dépasser $max caractères";
            return false;
        }
        return true;
    }

    /**
     * The field must be exactly $length characters/digits long (works with strings or numbers)
     *
     * USAGE : 'len:5'
     * @param string $field
     * @param string|int $length The length
     * @return bool
     */
    private function rule_len(string $field, string|int $length): bool
    {
        $data = (string)$this->data[$field];
        if (strlen($data ?? '') != $length) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit contenir $length caractères";
            return false;
        }
        return true;
    }

    /**
     * The field must contain at least one uppercase letter
     * Useful for passwords
     *
     * USAGE : 'containUpper'
     * @param string $field
     * @return bool
     */
    private function rule_containUpper(string $field): bool
    {
        $data = (string)$this->data[$field];
        if (!preg_match('/[A-Z]/', $data)) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit contenir au moins une majuscule";
            return false;
        }
        return true;
    }

    /**
     * The field must contain at least one lowercase letter
     * Useful for passwords
     *
     * USAGE : 'containLower'
     * @param string $field
     * @return bool
     */
    private function rule_containLower(string $field): bool
    {
        $data = (string)$this->data[$field];
        if (!preg_match('/[a-z]/', $data)) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit contenir au moins une minuscule";
            return false;
        }
        return true;
    }

    /**
     * The field must contain at least one number
     * Useful for passwords
     *
     * USAGE : 'containNumber'
     * @param string $field
     * @return bool
     */
    private function rule_containNumber(string $field): bool
    {
        $data = (string)$this->data[$field];
        if (!preg_match('/[0-9]/', $data)) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit contenir au moins un chiffre";
            return false;
        }
        return true;
    }

    /**
     * The field must contain at least one special character (anything but a letter or a number)
     * Useful for passwords
     *
     * USAGE : 'containSpecial'
     * @param string $field
     * @return bool
     * @see https://owasp.org/www-community/password-special-characters
     */
    private function rule_containSpecial(string $field): bool
    {
        $data = (string)$this->data[$field];
        if (!preg_match('/[^a-zA-Z0-9]/', $data)) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit contenir au moins un caractère spécial";
            return false;
        }
        return true;
    }
    //</editor-fold>


    // Number rules
    //<editor-fold desc="Number rules region">

    /**
     * The field must be a number (respect is_numeric())
     *
     * USAGE : 'numeric'
     * @param string $field
     * @return bool
     */
    private function rule_numeric(string $field): bool
    {
        if (!is_numeric($this->data[$field])) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être un nombre";
            return false;
        }
        return true;
    }

    /**
     * The field must be an integer (or is an integer as a string) (or is a float representing an integer like 1.0)
     *
     * USAGE : 'integer'
     * @param string $field
     * @return bool
     */
    private function rule_integer(string $field): bool
    {
        // Check if the value is an integer. Work with both string and int
        if (!is_numeric($this->data[$field]) || (int)$this->data[$field] != $this->data[$field]) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être un nombre entier";
            return false;
        }
        return true;
    }

    /**
     * The field must have a value greater or equal to $min (works with numbers)
     *
     * USAGE : 'min:5'
     * @param string $field
     * @param string|int $min
     * @return bool
     */
    private function rule_min(string $field, string|int $min): bool
    {
        if (intval($this->data[$field]) < intval($min)) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être supérieur ou égal à $min";
            return false;
        }
        return true;
    }

    /**
     * The field must have a value lower or equal to $max (works with numbers)
     *
     * USAGE : 'max:5'
     * @param string $field
     * @param string|int $max
     * @return bool
     */
    private function rule_max(string $field, string|int $max): bool
    {
        if (intval($this->data[$field]) > intval($max)) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être inférieur ou égal à $max";
            return false;
        }
        return true;
    }
    //</editor-fold>


    // Date rules
    //<editor-fold desc="Date rules region">

    /**
     * The field must be a string representing a valid date or a DateTimeInterface object
     * Exemple :
     *  - '2021-02-31' is not a valid date
     *  - '2021-02-28' is a valid date
     *  - '2021-02-28 12:00:00' is a valid date
     *  - '2021-02-28 12:00:00+02:00' is a valid date
     *
     * USAGE : 'date'
     * @param string $field
     * @return bool
     */
    private function rule_date(string $field): bool
    {
        $possibleDate = $this->data[$field];
        if ($possibleDate instanceof \DateTimeInterface) {
            return true;
        }

        if ($possibleDate === '') {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une date valide";
            return false;
        }

        try {
            new \DateTimeImmutable($possibleDate);

            // Check if the date was not a malformed string like "2021-02-31"
            if (!empty(\DateTimeImmutable::getLastErrors()['warning_count'])) {
                throw new \Exception();
            }

            return true;
        } catch (\TypeError|\Exception $e) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une date valide";
            return false;
        }
    }

    /**
     * The field must be a date before (or equal) to another field
     *
     * USAGE : 'beforeField:otherField_name'
     * @param string $field
     * @param string $otherField
     * @return bool
     */
    private function rule_beforeField(string $field, string $otherField): bool
    {
        if ($this->data[$otherField] !== null && $this->data[$field] !== null) {
            if ($this->data[$field] > $this->data[$otherField]) {
                $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être antérieur au champs {$this->humanFriendly($otherField)}";
                return false;
            }
        }
        return true;
    }

    /**
     * The field must be a date after (or equal) to another field
     *
     * USAGE : 'afterField:otherField_name'
     * @param string $field
     * @param string $otherField
     * @return bool
     */
    private function rule_afterField(string $field, string $otherField): bool
    {
        if ($this->data[$field] < $this->data[$otherField]) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être postérieur au champs {$this->humanFriendly($otherField)}";
            return false;
        }
        return true;
    }

    /**
     * The field must be a date before (or equal) to another date (or now if not specified)
     *
     * USAGE : 'before:2021-02-28'
     * @param string $field
     * @param string $date
     * @return bool
     */
    private function rule_before(string $field, string $date): bool
    {
        try {
            $fieldDate = new \DateTimeImmutable($this->data[$field]);
            $date = new \DateTimeImmutable($date);
        } catch (\Exception $e) {
            // If the date is not valid, the rule will simply return false
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une date valide";
            return false;
        }
        if ($fieldDate > $date) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être antérieur au {$date->format(self::DATE_FORMAT_IN_ERROR)}";
            return false;
        }
        return true;
    }

    /**
     * The field must be a date after (or equal) to another date (or now if not specified)
     *
     * USAGE : 'after:2021-02-28'
     * @param string $field
     * @param string $date
     * @return bool
     */
    private function rule_after(string $field, string $date): bool
    {
        try {
            $fieldDate = new \DateTimeImmutable($this->data[$field]);
            $date = new \DateTimeImmutable($date);
        } catch (\Exception $e) {
            // If the date is not valid, the rule will simply return false
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une date valide";
            return false;
        }
        if ($fieldDate < $date) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être postérieur au {$date->format(self::DATE_FORMAT_IN_ERROR)}";
            return false;
        }
        return true;
    }
    //</editor-fold>


    // Time rules
    //<editor-fold desc="Time rules region">


    /**
     * The field must be a string representing a valid time or a DateTimeInterface object
     * Exemple :
     *  - '25:00:00' is not a valid time
     *  - '12:00:00' is a valid time
     *  - '12:00:00+02:00' is a valid time
     *
     * USAGE : 'time'
     * @param string $field
     * @return bool
     */
    private function rule_time(string $field): bool
    {
        $possibleTime = $this->data[$field];
        if ($possibleTime instanceof \DateTimeInterface) {
            return true;
        }

        if ($possibleTime === '') {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une heure valide";
            return false;
        }

        try {
            new \DateTimeImmutable($possibleTime);

            // Check if the hour was not a malformed string like "25:00:00"
            if (!empty(\DateTimeImmutable::getLastErrors()['warning_count'])) {
                throw new \Exception();
            }

            return true;
        } catch (\TypeError|\Exception $e) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} doit être une heure valide";
            return false;
        }
    }
    //</editor-fold>


    // Database rules
    //<editor-fold desc="Database rules region">

    /**
     * The field must represent an existing data in the database. By default, the rule will check the id column of the entity
     *
     * USAGE : 'exists:' . User::class
     * USAGE : 'exists:App\Entity\User:email' (check in the email column instead of the id column)
     * @param string $field
     * @param string $entityClass The class of the entity to check
     * @param string|null $column The column to check (default to the id column) (this is the property name of the entity, not the real column name in the
     *                            database)
     * @return bool True if the data exists in the database, false otherwise
     * @throws \Exception If the entityClass or the column does not exist
     */
    private function rule_exists(string $field, string $entityClass, string|null $column = null): bool
    {
        $this->checkEntityManager();

        try {
            $column = $column ?? $this->em->getClassMetadata($entityClass)->getSingleIdentifierFieldName();
        } catch (ORMException $e) {
            throw new \Exception("The entity $entityClass does not exist");
        }

        try {
            $entity = $this->em->getRepository($entityClass)->findOneBy([$column => $this->data[$field]]);
        } catch (ORMException $e) {
            throw new \Exception("The column $column does not exist in the entity $entityClass");
        }

        if (!$entity) {
            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} n'existe pas";
            return false;
        }
        return true;
    }

    /**
     * The field must represent a non-existing entity in the database.
     * If $ignored is set, the rule will pass if the entity exists and is the same as the one represented by $ignored
     *
     * USAGE : 'unique:App\Entity\User'
     * USAGE : 'unique:' . User::class . ':email' (check in the email column instead of the id column)
     * USAGE : 'unique:\App\Entity\User:username:1' (check in the username column instead of the id column, but ignore the entity with the id 1)
     * @param string $field
     * @param string $entityClass The class of the entity to check
     * @param string|null $column
     * @param string|int|array|null $ignored
     * @return bool
     * @throws \Exception If the entityClass or the column does not exist
     */
    private function rule_unique(
        string $field,
        string $entityClass,
        string|null $column = null,
        string|int|array|null $ignored = null
    ): bool {
        $this->checkEntityManager();

        try {
            $column = $column ?? $this->em->getClassMetadata($entityClass)->getSingleIdentifierFieldName();
        } catch (ORMException $e) {
            throw new \Exception("The entity $entityClass does not exist");
        }

        try {
            $entities = $this->em->getRepository($entityClass)->findBy([$column => $this->data[$field]]);
        } catch (ORMException $e) {
            throw new \Exception("The column $column does not exist in the entity $entityClass");
        }

        if ($entities) {
            if ($ignored) {
                if (is_array($ignored)) { // If $ignored is an array, we check if the entity is in the array
                    foreach ($entities as $entity) {
                        if (!in_array($entity->getId(), $ignored)) {
                            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} n'est pas unique";
                            return false;
                        }
                    }
                }
                else { // If $ignored is not an array, we check if the entity is the same as the one represented by $ignored
                    foreach ($entities as $entity) {
                        if ($entity->getId() != $ignored) {
                            $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} n'est pas unique";
                            return false;
                        }
                    }
                }
            }
            else { // If $ignored is null, we directly return false because the entity exists
                $this->errors[$field][] = "Le champs {$this->humanFriendly($field)} n'est pas unique";
                return false;
            }
        }
        // If the entity doesn't exist
        return true;
    }
    //</editor-fold>
}
