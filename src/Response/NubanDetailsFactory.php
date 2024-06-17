<?php

namespace Wearesho\QoreId\Response;

use Wearesho\QoreId;

class NubanDetailsFactory
{
    use StatusTrait;
    use FieldMatchesTrait;

    public function createFromApiResponse(array $data): NubanDetails
    {
        $status = $this->getStatus($data);
        if ($status !== 'verified') {
            throw new QoreId\NoMatchException("Status {$status}, verified expected.", 101);
        }
        $fieldMatches = $this->getFieldMatches('nuban_check', $data);
        if (!array_key_exists('nuban', $data)) {
            throw new \InvalidArgumentException("Unable to find nuban data.", 102);
        }
        return new NubanDetails(
            $data['nuban']['firstname'] ?? '',
            $data['nuban']['lastname'] ?? '',
            $data['nuban']['middlename'] ?? '',
            $data['nuban']['accountName'] ?? '',
            $data['nuban']['accountNumber'] ?? '',
            $data['nuban']['dob'] ?? '',
            $data['nuban']['accountCurrency'] ?? '',
            $data['nuban']['mobileNumber'] ?? '',
            $data['nuban']['bvn'] ?? '',
            $fieldMatches
        );
    }
}
