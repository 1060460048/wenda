<?php
class RFLib_Utility_Dir
{
    /**
     * Get sub-directori(es) of a directory, not including the directory which
     * its name is one of ".", ".." or ".svn"
     *  
     * @param string $dir Path to the directory
     * @return array
     */
    public static function getSubdirs($dir)
    {
        if (!file_exists($dir)) {
            return array();
        }

        $subdirs = array();
        $dirIterator = new DirectoryIterator($dir);
        foreach ($dirIterator as $dir) {
            if ($dir->isDot() || !$dir->isDir()) {
                continue;
            }
            $dir = $dir->getFilename();
            if ('.svn' == $dir) {
                continue;
            } else {
            	$subdirs[] = $dir;
            }
        }
        return $subdirs;
    }
}
