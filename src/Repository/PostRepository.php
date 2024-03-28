<?php

namespace App\Repository;

use App\Contracts\Repository\Post\PostRepositoryInterface;
use App\Entity\Post;
use App\Util\Paginator\RequestConditionQueryWrapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public function __construct(
        ManagerRegistry                               $registry,
        private readonly RequestConditionQueryWrapper $queryWrapper,
    )
    {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function remove(Post $post): void
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }

    public function findByUlid(string $ulid): ?Post
    {
        return $this->findOneBy(['ulid' => $ulid]);
    }

    public function getPaginator(): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('post')
            ->addSelect(['images', 'owner'])
            ->leftJoin('post.images', 'images', Join::ON)
            ->join('post.owner', 'owner', Join::ON);

        return new Paginator(
            $this->queryWrapper->wrap($queryBuilder),
            fetchJoinCollection: true
        );
    }
}
