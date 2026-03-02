<?php

declare(strict_types=1);

namespace Models;

use Core\Database;

class Settings extends AbstractModel
{
    /**
     * Primary key
     *
     * @var string|null
     */
    protected static ?string $primaryKey = "idSettings";

    public ?int $idSettings = null {
        set => $this->idSettings = $value;
    }

    public ?int $idUser = null {
        set => $this->idUser = $value;
    }

    public bool $category_any = true {
        set => $this->category_any = (bool) $value;
    }

    public bool $category_programming = false {
        set => $this->category_programming = (bool) $value;
    }

    public bool $category_misc = false {
        set => $this->category_misc = (bool) $value;
    }

    public bool $category_dark = false {
        set => $this->category_dark = (bool) $value;
    }

    public bool $category_pun = false {
        set => $this->category_pun = (bool) $value;
    }

    public bool $category_spooky = false {
        set => $this->category_spooky = (bool) $value;
    }

    public bool $category_christmas = false {
        set => $this->category_christmas = (bool) $value;
    }

    public string $language_code = "en" {
        set => $this->language_code = $value;
    }

    public bool $blacklist_nsfw = false {
        set => $this->blacklist_nsfw = (bool) $value;
    }

    public bool $blacklist_religious = false {
        set => $this->blacklist_religious = (bool) $value;
    }

    public bool $blacklist_political = false {
        set => $this->blacklist_political = (bool) $value;
    }

    public bool $blacklist_racist = false {
        set => $this->blacklist_racist = (bool) $value;
    }

    public bool $blacklist_sexist = false {
        set => $this->blacklist_sexist = (bool) $value;
    }

    public bool $blacklist_explicit = false {
        set => $this->blacklist_explicit = (bool) $value;
    }

    public bool $safe_mode = true {
        set => $this->safe_mode = (bool) $value;
    }

    public bool $allow_single = true {
        set => $this->allow_single = (bool) $value;
    }

    public bool $allow_two_part = true {
        set => $this->allow_two_part = (bool) $value;
    }

    public int $joke_amount = 10 {
        set => $this->joke_amount = (int) $value;
    }

    protected array $casts = [];

    public static function All(): array
    {
        $pdo = Database::connection();

        $sql = "SELECT * FROM settings";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findByUserId(int $idUser): ?self
    {
        $pdo = Database::connection();

        $sql = "SELECT * FROM settings WHERE idUser = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUser' => $idUser]);
        $result = $stmt->fetchObject(self::class);

        return $result ?: null;
    }

    public function insert(): bool
    {
        $pdo = Database::connection();

        $sql = "INSERT INTO settings (idUser, category_any, category_programming, category_misc, category_dark, category_pun, category_spooky, category_christmas, language_code, blacklist_nsfw, 
        blacklist_religious, blacklist_political, blacklist_racist, blacklist_sexist, blacklist_explicit, safe_mode, allow_single, allow_two_part, joke_amount) VALUES (:idUser, :category_any, 
        :category_programming, :category_misc, :category_dark, :category_pun, :category_spooky, :category_christmas, :language_code, :blacklist_nsfw, :blacklist_religious, :blacklist_political, 
        :blacklist_racist, :blacklist_sexist, :blacklist_explicit, :safe_mode, :allow_single, :allow_two_part, :joke_amount)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idUser' => $this->idUser,
            ':category_any' => (int) $this->category_any,
            ':category_programming' => (int) $this->category_programming,
            ':category_misc' => (int) $this->category_misc,
            ':category_dark' => (int) $this->category_dark,
            ':category_pun' => (int) $this->category_pun,
            ':category_spooky' => (int) $this->category_spooky,
            ':category_christmas' => (int) $this->category_christmas,
            ':language_code' => $this->language_code,
            ':blacklist_nsfw' => (int) $this->blacklist_nsfw,
            ':blacklist_religious' => (int) $this->blacklist_religious,
            ':blacklist_political' => (int) $this->blacklist_political,
            ':blacklist_racist' => (int) $this->blacklist_racist,
            ':blacklist_sexist' => (int) $this->blacklist_sexist,
            ':blacklist_explicit' => (int) $this->blacklist_explicit,
            ':safe_mode' => (int) $this->safe_mode,
            ':allow_single' => (int) $this->allow_single,
            ':allow_two_part' => (int) $this->allow_two_part,
            ':joke_amount' => $this->joke_amount,
        ]);


        if ($stmt->rowCount() > 0) {
            $this->idSettings = (int) $pdo->lastInsertId();
        }

        return $stmt->rowCount() > 0;
    }

    public function update(): bool
    {
        $pdo = Database::connection();

        $sql = "UPDATE settings SET category_any = :category_any, category_programming = :category_programming, category_misc = :category_misc, category_dark = :category_dark, 
        category_pun = :category_pun, category_spooky = :category_spooky, category_christmas = :category_christmas, language_code = :language_code, blacklist_nsfw = :blacklist_nsfw, 
        blacklist_religious = :blacklist_religious, blacklist_political = :blacklist_political, blacklist_racist = :blacklist_racist, blacklist_sexist = :blacklist_sexist, 
        blacklist_explicit = :blacklist_explicit, safe_mode = :safe_mode, allow_single = :allow_single, allow_two_part = :allow_two_part, joke_amount = :joke_amount WHERE idSettings = :idSettings AND idUser = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idSettings' => $this->idSettings,
            ':idUser' => $this->idUser,
            ':category_any' => (int) $this->category_any,
            ':category_programming' => (int) $this->category_programming,
            ':category_misc' => (int) $this->category_misc,
            ':category_dark' => (int) $this->category_dark,
            ':category_pun' => (int) $this->category_pun,
            ':category_spooky' => (int) $this->category_spooky,
            ':category_christmas' => (int) $this->category_christmas,
            ':language_code' => $this->language_code,
            ':blacklist_nsfw' => (int) $this->blacklist_nsfw,
            ':blacklist_religious' => (int) $this->blacklist_religious,
            ':blacklist_political' => (int) $this->blacklist_political,
            ':blacklist_racist' => (int) $this->blacklist_racist,
            ':blacklist_sexist' => (int) $this->blacklist_sexist,
            ':blacklist_explicit' => (int) $this->blacklist_explicit,
            ':safe_mode' => (int) $this->safe_mode,
            ':allow_single' => (int) $this->allow_single,
            ':allow_two_part' => (int) $this->allow_two_part,
            ':joke_amount' => $this->joke_amount,
        ]);

        return $stmt->rowCount() > 0;
    }
}