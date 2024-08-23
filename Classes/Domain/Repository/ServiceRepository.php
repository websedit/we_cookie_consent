<?php

namespace Websedit\WeCookieConsent\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/***
 *
 * This file is part of the "we_cookie_consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 websedit AG <extensions@websedit.de>
 *
 ***/

/**
 * The repository for Services
 */
class ServiceRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING
    ];


    /**
     * Get the sorting value of a category by its UID.
     *
     * @param int $uid The UID of the category
     * @return int|null The sorting value or null if not found
     */
    public function getCategorySortingByUid(int $uid): ?int
    {
        // Get the query builder for the sys_category table
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        // Build the query to fetch the sorting value
        $sorting = $queryBuilder
            ->select('sorting')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchOne();

        return $sorting !== false ? (int)$sorting : null;
    }
}
