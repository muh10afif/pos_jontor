<?php 

    foreach ($post['nm_kategori'] as $k) {
        echo $k."<br>";

        $i = 0;
        foreach ($post['nm_produk'] as $p) {
            
            if ($k == $post['kategori'][$i]) {
                echo $p." ".$post['qty_list'][$i]."x".$post['harga_list'][$i]." (".$post['diskon_list'][$i].") ".$post['subtotal_list'][$i]."<br>";
            }

        $i++;
        }
    }

    
?>
<?= $post['total_diskon']."<br>"; ?>
<?= $post['total_harga']."<br>"; ?>
<?= $post['tunai']."<br>"; ?>
<?= $post['kembalian'] ?>