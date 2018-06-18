<?php declare(strict_types=1);

namespace App\Decoder;

/**
 * Decodes JSON data and make it compliant with application/x-www-form-encoded style.
 */
class JsonToFormDecoder implements DecoderInterface
{
    
    /**
     * Makes data decoded from JSON application/x-www-form-encoded compliant.
     *
     * @param array $data
     */
    private function xWwwFormEncodedLike(&$data)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                // Encode recursively
                $this->xWwwFormEncodedLike($value);
            } else {
                if (false === $value) {
                    // Checkbox-like behavior removes false data but PATCH HTTP method with just checkboxes does not work
                    $value = null;
                } else {
                    if (!\is_string($value)) {
                        // Convert everything to string
                        // true values will be converted to '1', this is the default checkbox behavior
                        $value = (string) $value;
                    }
                }
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function decode($data)
    {
        $decodedData = @json_decode($data, true);
        if ($decodedData) {
            $this->xWwwFormEncodedLike($decodedData);
        }
        
        return $decodedData;
    }
}
