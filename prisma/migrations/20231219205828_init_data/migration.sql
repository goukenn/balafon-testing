-- DropForeignKey
ALTER TABLE `tbprisma_tablename` DROP FOREIGN KEY `tbprisma_tablename_ibfk_1`;

-- AlterTable
ALTER TABLE `tbprisma_tablename` MODIFY `clName` VARCHAR(140) NOT NULL;

-- CreateTable
CREATE TABLE `tbprisma_authors` (
    `clId` INTEGER NOT NULL AUTO_INCREMENT,
    `clFirstName` VARCHAR(140) NOT NULL,
    `clLastName` VARCHAR(140) NOT NULL,
    `clContact` VARCHAR(140) NULL,
    `clDesc` VARCHAR(191) NULL,
    `clParent` INTEGER NOT NULL,
    `clBaseType` ENUM('CAR') NOT NULL DEFAULT 'CAR',

    PRIMARY KEY (`clId`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- AddForeignKey
ALTER TABLE `tbprisma_tablename` ADD CONSTRAINT `tbprisma_tablename_clParent_fkey` FOREIGN KEY (`clParent`) REFERENCES `tbprisma_tablename`(`clId`) ON DELETE SET NULL ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tbprisma_authors` ADD CONSTRAINT `tbprisma_authors_clParent_fkey` FOREIGN KEY (`clParent`) REFERENCES `tbprisma_tablename`(`clId`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- RenameIndex
ALTER TABLE `tbprisma_tablename` RENAME INDEX `clName` TO `tbprisma_tablename_clName_key`;
