<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RequestConversionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     	normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     	denormalizationContext={"groups"={"write"}, "enable_max_depth"=true},
 *     itemOperations={
 * 		"get",
 * 	    "put",
 * 	   "delete",
 *     "get_change_logs"={
 *              "path"="/request_conversions/{id}/change_log",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Changelogs",
 *                  "description"="Gets al the change logs for this resource"
 *              }
 *          },
 *     "get_audit_trail"={
 *              "path"="/request_conversions/{id}/audit_trail",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Audittrail",
 *                  "description"="Gets the audit trail for this resource"
 *              }
 *          }
 * 		},
 * )
 * @ORM\Entity(repositoryClass=RequestConversionRepository::class)
 * @Gedmo\Loggable(logEntryClass="Conduction\CommonGroundBundle\Entity\ChangeLog")
 *
 * @ApiFilter(BooleanFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @ApiFilter(DateFilter::class, strategy=DateFilter::EXCLUDE_NULL)
 * @ApiFilter(SearchFilter::class)
 */
class RequestConversion
{
    /**
     * @var UuidInterface The UUID identifier of this resource
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Assert\Uuid
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string The contact for this organization
     *
     * @Groups({"read", "write"})
     * @Assert\Url
     * @Assert\Length(
     *     max=255
     * )
     */
    private $request;

    /**
     * @var string The message that is logged to the token component
     *
     * @Groups({"read"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @var string The status that is logged to the token component **OK or FAILED**
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string The url of the message that is stored to the TRC
     * @Groups({"read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $result;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(string $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }
}
