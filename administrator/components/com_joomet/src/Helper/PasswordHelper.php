<?php
/**
 * @package                                     NXD NextCloud Events Module
 *
 * @author                                      nx-designs | Marco Rensch <support@nx-designs.ch>
 * @copyright                                   Copyright(R) 2024 by nx-designs | Marco Rensch
 * @license                                     GNU General Public License version 2 or later; see LICENSE.txt
 * @link                                        https://www.nx-designs.ch
 * @since                                       2.0.0
 *
 */

namespace NXD\Component\Joomet\Administrator\Helper;

class PasswordHelper
{
	private string $cipher = "AES-128-CTR";
	private string $key = "NXDprotectedPassword2025Secondo";
	private int $options = 0;
	private string $crypt_iv = "1734597897211829";

	public function __construct()
	{

	}

	public function encrypt(string $password_plain): string
	{
		$encrypted = openssl_encrypt($password_plain, $this->cipher, $this->key, $this->options, $this->crypt_iv);
		return base64_encode($encrypted);
	}

	public function decrypt(string $encrypted_password): string
	{
		$encrypted_password = base64_decode($encrypted_password);
		return openssl_decrypt($encrypted_password, $this->cipher, $this->key, $this->options, $this->crypt_iv);
	}

}