<?php

namespace Shopbox\Models\Zpanel\Traits;

use Shopbox\Models\Zpanel\ConfirmationToken;
use Shopbox\Models\Zpanel\VerificationToken;

trait HasConfirmationTokens
{
	public function generateConfirmationToken()
	{
		$this->confirmationToken()->create([
			'token' => $token = str_random(200),
			'expires_at' => $this->getConfirmationTokenExpiry()
		]);

		return $token;
	}

	public function generateVerificationToken()
	{

		$this->verificationToken()->create([
			'token' => $token = str_random(200),
			'expires_at' => $this->getConfirmationTokenExpiry()
		]);
		
		return $token;
	}

	protected function getConfirmationTokenExpiry()
	{
		return $this->freshTimestamp()->addMinutes(60);
	}

	public function confirmationToken()
	{
		return $this->hasOne(ConfirmationToken::class);
	}

	public function verificationToken()
	{
		return $this->hasOne(VerificationToken::class);
	}
}