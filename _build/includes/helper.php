<?php
/**
 * Helper class to help build the transport package
 */
class Helper
{
    /** @var \modX $modx An instance of the modX object */
    public $modx;

    /**
     * Construct the object
     *
     * @param modX $modx A modX instance
     * @param array $options
     */
    public function __construct(modX &$modx, array $options = array())
    {
        $this->modx =& $modx;
    }

    /**
     * Formats the given file to be used as snippet/plugin content
     *
     * @param string $filename The path the to snippet file
     *
     * @return string The PHP content
     */
    public static function getPHPContent($filename)
    {
        $o = file_get_contents($filename);
        $o = str_replace('<?php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);

        return $o;
    }

    /**
     * Format the given array of modAccessPolicy
     *
     * @param array $permissions
     *
     * @return string JSON encoded
     */
    public function buildPolicyFormatData(array $permissions)
    {
        $data = array();
        /** @var modAccessPolicy $permission */
        foreach ($permissions as $permission) {
            $data[$permission->get('name')] = true;
        }

        $data = json_encode($data);

        return $data;
    }

}
