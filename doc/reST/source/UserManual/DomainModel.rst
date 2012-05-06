Domain Model
============

This section describes the eGov API domain model. The domain model may be accessed directly when
using Fluid-based templates. If instead you use marker-based templates, only partial access to the
domain model will be possible and you probably will have to implement one of the available hooks
(see `Hooks`_ section in `Developer manual`_) for more complex scenarios.

We only describe domain model for the eGov API levels (audience, view, domain, topic, service).
Attributes for the blocks of information which are typically used for the details view of an entity
should be analyzed with Fluid template examples or by looking at the classes within directory
``Classes/Domain/Model/Blocks/`` (e.g., using http://api.causal.ch/egovapi/).

.. _tx_egovapi_domain_model_audience:

Audience (``tx_egovapi_domain_model_audience``)
-----------------------------------------------

======================   =========
 Property                 Type
======================   =========
 id                       integer
 author                   string
 creationDate             integer (timestamp)
 lastModificationDate     integer (timestamp)
 name                     string
 views                    tx_egovapi_domain_model_view_ []
======================   =========

.. _tx_egovapi_domain_model_view:

View (``tx_egovapi_domain_model_view``)
---------------------------------------

======================   =========
 Property                 Type
======================   =========
 id                       integer
 author                   string
 creationDate             integer (timestamp)
 lastModificationDate     integer (timestamp)
 name                     string
 domains                  tx_egovapi_domain_model_domain_ []
 audience                 tx_egovapi_domain_model_audience_
======================   =========

Additional template values:

- **hasParent** (boolean): set to TRUE if parent view may be shown on same page, otherwise FALSE.

.. _tx_egovapi_domain_model_domain:

Domain (``tx_egovapi_domain_model_domain``)
-------------------------------------------

======================   =========
 Property                 Type
======================   =========
 id                       integer
 author                   string
 creationDate             integer (timestamp)
 lastModificationDate     integer (timestamp)
 name                     string
 description              string
 isParent                 boolean
 versionId                integer
 versionName              string
 communityId              string
 release                  integer
 remarks                  string
 status                   string
 generalInformation       tx_egovapi_domain_model_block_generalInformation
 news                     tx_egovapi_domain_model_block_news
 subdomains               tx_egovapi_domain_model_block_subdomains
 descriptor               tx_egovapi_domain_model_block_descriptor
 synonym                  tx_egovapi_domain_model_synonym
 topics                   tx_egovapi_domain_model_topic_ []
 view                     tx_egovapi_domain_model_view_
======================   =========

Additional template values:

- **hasParent** (boolean): set to TRUE if parent view may be shown on same page, otherwise FALSE.
- **showLevelInformation** (boolean): set to TRUE if level information block may be shown,
  otherwise FALSE.
- **showGeneralInformation** (boolean): set to TRUE if general information block may be shown,
  otherwise FALSE.
- **showNews** (boolean): set to TRUE if news block may be shown, otherwise FALSE.
- **showSubdomains** (boolean): set to TRUE if subdomains block may be shown, otherwise FALSE.
- **showDescriptor** (boolean): set to TRUE if descriptor block may be shown, otherwise FALSE.
- **showSynonym** (boolean): set to TRUE if synonym block may be shown, otherwise FALSE.

.. _tx_egovapi_domain_model_topic:

Topic (``tx_egovapi_domain_model_topic``)
-----------------------------------------

======================   =========
 Property                 Type
======================   =========
 id                       integer
 author                   string
 creationDate             integer (timestamp)
 lastModificationDate     integer (timestamp)
 name                     string
 description              string
 isParent                 boolean
 versionId                integer
 versionName              string
 communityId              string
 release                  integer
 remarks                  string
 status                   string
 generalInformation       tx_egovapi_domain_model_block_generalInformation
 descriptor               tx_egovapi_domain_model_block_descriptor
 synonym                  tx_egovapi_domain_model_synonym
 services                 tx_egovapi_domain_model_service_ []
 domain                   tx_egovapi_domain_model_domain_
======================   =========

Additional template values:

- **hasParent** (boolean): set to TRUE if parent view may be shown on same page, otherwise FALSE.
- **showLevelInformation** (boolean): set to TRUE if level information block may be shown,
  otherwise FALSE.
- **showGeneralInformation** (boolean): set to TRUE if general information block may be shown,
  otherwise FALSE.
- **showNews** (boolean): set to TRUE if news block may be shown, otherwise FALSE.
- **showSubtopics** (boolean): set to TRUE if subtopics block may be shown, otherwise FALSE.
- **showDescriptor** (boolean): set to TRUE if descriptor block may be shown, otherwise FALSE.
- **showSynonym** (boolean): set to TRUE if synonym block may be shown, otherwise FALSE.

.. _tx_egovapi_domain_model_service:

Service (``tx_egovapi_domain_model_service``)
---------------------------------------------

======================   =========
 Property                 Type
======================   =========
 id                       integer
 author                   string
 creationDate             integer (timestamp)
 lastModificationDate     integer (timestamp)
 name                     string
 description              string
 isParent                 boolean
 versionId                integer
 versionName              string
 communityId              string
 release                  integer
 comments                 string
 provider                 string
 customer                 string
 type                     string
 action                   string
 status                   string
 generalInformation       tx_egovapi_domain_model_block_generalInformation
 prerequisites            tx_egovapi_domain_model_block_prerequisites
 procedure                tx_egovapi_domain_model_block_procedure
 forms                    tx_egovapi_domain_model_block_forms
 documentsRequired        tx_egovapi_domain_model_block_documentsRequired
 serviceProvided          tx_egovapi_domain_model_block_serviceProvided
 fee                      tx_egovapi_domain_model_block_fee
 legalRegulation          tx_egovapi_domain_model_block_legalRegulation
 documentsOther           tx_egovapi_domain_model_block_documentsOther
 remarks                  tx_egovapi_domain_model_block_remarks
 approval                 tx_egovapi_domain_model_block_approval
 contact                  tx_egovapi_domain_model_block_contact_
 topic                    tx_egovapi_domain_model_topic_
======================   =========

Additional template values:

- **hasParent** (boolean): set to TRUE if parent view may be shown on same page, otherwise FALSE.
- **showLevelInformation** (boolean): set to TRUE if level information block may be shown,
  otherwise FALSE.
- **showGeneralInformation** (boolean): set to TRUE if general information block may be shown,
  otherwise FALSE.
- **showPrerequisites** (boolean): set to TRUE if prerequisites block may be shown, otherwise
  FALSE.
- **showProcedure** (boolean): set to TRUE if procedure block may be shown, otherwise FALSE.
- **showForms** (boolean): set to TRUE if forms block may be shown, otherwise FALSE.
- **showDocumentsRequired** (boolean): set to TRUE if documents required block may be shown,
  otherwise FALSE.
- **showServiceProvided** (boolean): set to TRUE if service provided block may be shown, otherwise
  FALSE.
- **showFee** (boolean): set to TRUE if fee block may be shown, otherwise FALSE.
- **showLegalRegulation** (boolean): set to TRUE if legal regulation block may be shown, otherwise
  FALSE.
- **showDocumentsOther** (boolean): set to TRUE if documents other block may be shown, otherwise
  FALSE.
- **showRemarks** (boolean): set to TRUE if remarks block may be shown, otherwise FALSE.
- **showApproval** (boolean): set to TRUE if approval block may be shown, otherwise FALSE.
- **showContact** (boolean): set to TRUE if contact block may be shown, otherwise FALSE.
- **showBackToList** (boolean): set to TRUE if back to list block may be shown, otherwise FALSE.

.. _tx_egovapi_domain_model_block_contact:

Service (``tx_egovapi_domain_model_block_contact``)
---------------------------------------------------

======================   =========
 Property                 Type
======================   =========
 department               string
 office                   string
 address                  string
 postalCase               string
 postalCode               string
 locality                 string
 person                   string
 phone                    string
 fax                      string
 email                    string
 publicKey                string
 logo                     string
 banner                   string
 openingHours             string
======================   =========