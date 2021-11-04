<?php

namespace App\Traits;

use Illuminate\Contracts\Encryption\DecryptException;

trait Encryptable {

    /**
     * If the attribute is in the encryptable array
     * then decrypt it.
     *
     * @param string $key
     *
     * @return string|null $value
     */
    public function getAttribute($key): ?string {
        $value = parent::getAttribute($key);
        try {
            if(!in_array($key, $this->encryptable) || $value == '' || $value === null) {
                return $value;
            }

            return decrypt($value);
        } catch(DecryptException $exception) {
            report($exception);
            return $value;
        }
    }

    /**
     * If the attribute is in the encryptable array
     * then encrypt it.
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
    public function setAttribute($key, $value): mixed {
        if(in_array($key, $this->encryptable) && $value !== null) {
            $value = encrypt($value);
        }
        return parent::setAttribute($key, $value);
    }

    /**
     * When need to make sure that we iterate through
     * all the keys.
     *
     * @return array
     */
    public function attributesToArray(): array {
        $attributes = parent::attributesToArray();
        foreach($this->encryptable as $key) {
            if(isset($attributes[$key])) {
                $attributes[$key] = decrypt($attributes[$key]);
            }
        }
        return $attributes;
    }
}
