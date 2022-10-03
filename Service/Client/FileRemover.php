<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Service\Client;

class FileRemover
{
    protected \Magento\Framework\Filesystem\Io\File $ioFile;
    protected \MageSuite\ErpConnector\Model\Command\LogErrorMessage $logErrorMessage;

    public function __construct(
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \MageSuite\ErpConnector\Model\Command\LogErrorMessage $logErrorMessage
    ) {
        $this->ioFile = $ioFile;
        $this->logErrorMessage = $logErrorMessage;
    }

    public function removeFiles($client)
    {
        $destinationDir = $client->getFullPath($client->getData('destination_dir'));
        $provider = $client->getData('provider');

        $removedFiles = new \Magento\Framework\DataObject([
            'first_file_name' => null,
            'last_file_name' => null,
            'count' => 0
        ]);

        $connection = $client->getConnection();

        try {
            $client->validateDirectoryExist($destinationDir);

            $connection->cd($destinationDir);
            $files = $connection->ls(\Magento\Framework\Filesystem\Io\File::GREP_FILES);

            $minDate = (new \DateTime())->modify(sprintf('-%s days', $client->getData('remove_files_order_than_days_ago')));

            $count = 0;
            foreach ($files as $file) {
                $fileDate = new \DateTime($file['mod_date']);
                $fileName = $file['text'];

                if ($fileDate > $minDate || !$client->isValidFileName($fileName)) {
                    continue;
                }

                if (!$count) {
                    $removedFiles->setFirstFileName($fileName);
                }

                $removedFiles->setLastFileName($fileName);
                $connection->rm(sprintf(\MageSuite\ErpConnector\Model\Client\ClientInterface::FILE_PATH_FORMAT, $destinationDir, $fileName));
                $count++;

                if ($count >= $client->getData('batch_size')) {
                    break;
                }
            }

            $removedFiles->setCount($count);

        } catch (\Exception $e) {
            $this->logErrorMessage->execute(
                sprintf(\MageSuite\ErpConnector\Model\Client\ClientInterface::ERROR_MESSAGE_TITLE_FORMAT, $provider->getName()),
                $e->getMessage()
            );

            throw $e;
        }

        $client->closeConnection($connection);
        return $removedFiles;
    }
}
