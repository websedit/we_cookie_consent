<?php

namespace Websedit\WeCookieConsent\Domain\Repository;

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
class ServiceRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
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
        $queryBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        // Build the query to fetch the sorting value
        $sorting = $queryBuilder
            ->select('sorting')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
            )
            ->execute();

        return $sorting !== false ? (int)$sorting : null;
    }
}