RDF Rendering (Semantic Web)
============================

Since egovapi version 2.0, in 2012, RDF output of the services has been introduced,
allowing published services to be easily integrated to the semantic web as a source
of open data.

For new installations, the RDF rendering engine is automatically activated because
the associated database table tx_egovapi_rdf is known to be present as it is
automatically created when installing the extension.

For existing installations, the RDF rendering engine should be manually activated by
opening egovapi within the Extension Manager, creating any missing tables and making
sure the corresponding checkbox is ticked in the Basic settings.

**Beware:*** Do not forget to click the Update button to save the configuration!

In order for external semantic web crawlers to take advantage of the RDF output of the
published services, a reference should be added to the HEAD part of website's homepage:

::

	<link id="__ech-published_services" rel="alternate" type="text/rdf+n3" href="/?eID=egovapi_rdf" />

This may be easily done with such TypoScript configuration, typically within your master template:

::

	page.headerData.999 = TEXT
	page.headerData.999 {
    	typolink {
        	parameter = <your-homepage-page-id>
        	additionalParams = &eID=egovapi_rdf
        	returnLast = url
    	}
    	wrap = <link id="__ech-published_services" rel="alternate" type="text/rdf+n3" href="|" />
	}

Internals about the RDF rendering engine
----------------------------------------

Each time the main egovapi plugin (pi1) renders the SINGLE view of a service, it adds
or refreshes an entry in the database table tx_egovapi_rdf. This table contains
information on the corresponding service (id, version , ...) and a reference to the
page URL it is published to.

In additional, a column stores the last time the service has been "seen" (that is, the
main egovapi plugin generated the SINGLE view). This column allows obsolete entries to
be automatically pruned after a grace period of 60 days.

What it means is that published services that are not accessed by someone at least once
every 2 months or services that moved to another URL will automatically disappear from
the generated RDF file.