<?php

/**
 * An extension of the data extension class which allows pages to use Google site verification.
 */
class GoogleSiteVerificationExtension extends DataExtension
{
    /**
     * Appends additional meta tags to the extended page markup.
     *
     * @param string $tags
     */
    public function MetaTags(&$tags)
    {
        if ($Config = SiteConfig::current_site_config()) {
            
            if ($Code = trim($Config->GoogleSiteVerificationCode)) {
                
                $tags .= "<meta name=\"google-site-verification\" content=\"{$Code}\" />\n";
                
            }
            
        }
    }
}
