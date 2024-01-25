<?php

namespace Wearesho\QoreId\Tests\Response;

use PHPUnit\Framework\TestCase;
use Wearesho\QoreId;

final class NinPhoneFactoryTest extends TestCase
{
    public function testCreateFromDataArray(): void
    {
        $data = [
            "status" => [
                "status" => "verified"
            ],
            "nin" => [
                "nin" => "63184876213",
                "firstname" => "Bunch",
                "lastname" => "Dillon",
                "middlename" => "John",
                "phone" => "08000000000",
                "gender" => "m",
                "birthdate" => "06-01-1974",
                "photo" => "base 64 encoded",
                "address" => "5, Adeniyi Jones, Ikeja, Lagos"
            ],
            "summary" => [
                "nin_check" => [
                    "status" => "EXACT_MATCH",
                    "fieldMatches" => [
                        "firstname" => true,
                        "lastname" => true
                    ]
                ]
            ],
        ];

        $factory = new QoreId\Response\NinPhoneFactory();
        $ninPhone = $factory->createFromApiResponse($data);

        $this->assertSame("verified", $ninPhone->getStatus());
        $this->assertSame(63184876213, $ninPhone->getNin());
        $this->assertSame(['firstname' => true, 'lastname' => true], $ninPhone->getFieldMatches());
        $details = $ninPhone->getDetails();
        unset($details['nin']);
        $this->assertSame([
            'firstname' => "Bunch",
            "lastname" => "Dillon",
            "middlename" => "John",
            "phone" => "08000000000",
            "gender" => "m",
            "birthdate" => "06-01-1974",
            "photo" => "base 64 encoded",
            "address" => "5, Adeniyi Jones, Ikeja, Lagos"
        ], $details);
    }

    public function testCreatewFromDataArrayMismatch(): void
    {
        $data = [
            "id" => 10122355,
            "applicant" => [
                "firstname" => "Abuja",
                "lastname" => "Federal"
            ],
            "summary" => [
                "nin_check" => [
                    "status" => "NO_MATCH",
                    "fieldMatches" => [
                        "firstname" => false,
                        "lastname" => false
                    ]
                ]
            ],
            "status" => [
                "state" => "complete",
                "status" => "id_mismatch"
            ]
        ];

        $factory = new QoreId\Response\NinPhoneFactory();
        $ninPhone = $factory->createFromApiResponse($data);

        $this->assertSame('id_mismatch', $ninPhone->getStatus());
        $this->assertSame([
            "firstname" => false,
            "lastname" => false
        ], $ninPhone->getFieldMatches());
        $this->assertNull($ninPhone->getNin());
        $this->assertEmpty($ninPhone->getDetails());
    }

    public function testCreateFromDataArrayWithMissingKeys(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data array: required status key is missing.');

        $data = [];
        $factory = new QoreId\Response\NinPhoneFactory();
        $factory->createFromApiResponse($data);
    }

    public function testMissingStatus(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data array: required status key is missing.');

        $data = [
            "nin" => ["nin" => "12345"],
            "summary" => ["nin_check" => ["fieldMatches" => []]]
        ];
        $factory = new QoreId\Response\NinPhoneFactory();
        $factory->createFromApiResponse($data);
    }

    public function testMissingNin(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data array: required nin key is missing.');

        $data = [
            "status" => ["status" => "verified"],
            "summary" => ["nin_check" => ["fieldMatches" => []]]
        ];
        $factory = new QoreId\Response\NinPhoneFactory();
        $factory->createFromApiResponse($data);
    }

    public function testMissingFieldMatches(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data array: required fieldMatches key is missing.');

        $data = [
            "status" => ["status" => "verified"],
            "nin" => ["nin" => "99999999999"]
        ];
        $factory = new QoreId\Response\NinPhoneFactory();
        $factory->createFromApiResponse($data);
    }
}
