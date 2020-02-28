<?php

namespace Chirp;

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.

use DirectoryIterator;
use SplFileInfo;
use UnexpectedValueException;

class FileList
{

    private $keys = ['type', 'pathname', 'mime_type', 'size']; // default values to extract
    private $filters = [];
    private $recurse = FALSE;
    private $depth = 0;

    public function set_keys(...$keys)
    {
        $this->keys = $keys;
    }

    public function recurse($depth = 0)
    {
        $this->recurse = TRUE;
        $this->depth = $depth;
    }

    public function scan($dir, $filter = null, $filter_opt = null, $depth = 0)
    {
        if (!empty($filter) and is_array($filter_opt)) {
            $this->add_filter($filter, $filter_opt);
        }
        $retval = [];

        if ($this->recurse && $this->depth && ($depth > $this->depth)) {
            return $retval;
        }

        if (substr($dir, -1) != "/") { // add trailing slash if missing
            $dir .= "/";
        }

        try { // open directory for reading
            $d = new DirectoryIterator($dir);
        } catch (UnexpectedValueException $e) {
            error_log(__METHOD__ . ": " . $e->getMessage());
            return $retval;
        }

        foreach ($d as $fileinfo) {
            if ($fileinfo->isDot()) { // skip hidden files
                continue;
            }
            if ($this->recurse && ('dir' === $this->extract($fileinfo, 'type'))) {
                $dir_contents = $this->scan($this->extract($fileinfo, 'pathname'), $depth + 1);
                if ($dir_contents) {
                    $retval = array_merge($retval, $dir_contents);
                    continue;
                }
            }
            $file_struct = [];
            foreach (array_unique(array_merge(array_keys($this->filters), $this->keys)) as $key) {
                $file_struct[$key] = $this->extract($fileinfo, $key);
                if (isset($this->filters[$key]) && !in_array($file_struct[$key], $this->filters[$key])) {
                    continue 2;
                }
            }
            $retval[] = $file_struct;
        }

        if (!empty($filter) and is_array($filter_opt)) {
            $this->removeFilters($filter, $filter_opt);
        }

        return $retval;
    }

    public function add_filter($key, array $values)
    {
        $this->filters[$key] = $values;
    }

    private function extract(SplFileInfo $finfo, $key)
    {
        switch ($key) {
            case 'basename':
                return $finfo->getBasename("." . $finfo->getExtension());

            case 'ext':
                return $finfo->getExtension();

            case 'filename':
                return $finfo->getFilename();

            case 'imagesize':
                return getimagesize($finfo->getRealpath());

            case 'mime_type':
                return mime_content_type($finfo->getRealpath());

            case 'mtime':
                return $finfo->getMTime();

            case 'path':
                return $finfo->getPath();

            case 'pathname':
                return $finfo->getPathname();

            case 'realpath':
                return $finfo->getRealPath();

            case 'size':
                return $finfo->getSize();

            case 'type':
                return $finfo->getType();

            default:
                error_log(__METHOD__ . ": unsupported key: '{$key}'");
                $this->keys = array_diff($this->keys, [$key]);
                unset($this->filters[$key]);
                return FALSE;

        }
    }

    public function removeFilters($key, $values = null)
    {
        if (!empty($value)) {
            if (!is_array($values)) {
                $values = [$values];
            }
            foreach ($values as $value) {
                unset($this->filters[$key][$value]);
            }
        } else {
            unset($this->filters[$key]);
        }
    }

}