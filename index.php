<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Character Search</title>
<link  rel="stylesheet" href="css/my_Style.css" type="text/css"/>
</head>

<body>
<h3>welcome at</h3><h1>Character Search</h1>
<form action="index.php" method="get">
<input  type="text" placeholder="Search" width="80"  name="q" />
<input id="btn"  type="submit"  value="Search"/>

</form>
<?php

$f1=fopen("A.txt","r")or die("Unable to open file!");
$f2=fopen("B.txt","r")or die("Unable to open file!");
$f3=fopen("C.txt","r")or die("Unable to open file!");
$f4=fopen("D.txt","r")or die("Unable to open file!");
$f5=fopen("E.txt","r")or die("Unable to open file!");
function Read($f)
{
	while(!feof($f)){
	 $s.=fgets($f);
	}
	rewind($f);
	 return $s;
}

function Different_Terms($f)
{
	$s=Read($f);
$diff=trim(count_chars($s,3));
$diff=strtolower($diff);
return $diff;
}

function termfreq($array,$f)
{
$s=Read($f);
$s=strtolower($s);
$count=0;
$max=0;
//calculating frequency
foreach($array as $term)
{
	$freq[$count]=substr_count($s,$term);
	
	if($freq[$count]>$max)
	$max=$freq[$count];
	$count++;
}

//calculating term frequency
for($i=0;$i<$count;$i++)
$tf[$i]=$freq[$i]/$max;

return $tf;
}
function docfreq($char,$a1,$a2,$a3,$a4,$a5,$a6)
{
	$df=0;
	foreach($a1 as $term)
	{
		if($term==$char)
		{
			$df++;
			break;
		}
	}
	foreach($a2 as $term)
	{
		if($term==$char)
		{
			$df++;
			break;
		}
	}
	foreach($a3 as $term)
	{
		if($term==$char)
		{
			$df++;
			break;
		}
	}
	foreach($a4 as $term)
	{
		if($term==$char)
		{
			$df++;
			break;
		}
	}
	foreach($a5 as $term)
	{
		if($term==$char)
		{
			$df++;
			break;
		}
	}
	foreach($a6 as $term)
	{
		if($term==$char)
		{
			$df++;
			break;
		}
	}
	return $df;
}

/*function docfreq($a1,$a2,$a3,$a4,$a5)
{
	$counter=array_fill(0,count($a1),1);
	for($i=0;$i<count($a1);$i++)
	{
		foreach($a2 as $term)
		{
			if($a1[$i]==$term)
			{
				$counter[$i]++;
				break;	
			}
		}
			foreach($a3 as $term)
		{
			if($a1[$i]==$term)
			{
				$counter[$i]++;
				break;	
			}
		}
		
		foreach($a4 as $term)
		{
			if($a1[$i]==$term)
			{
				$counter[$i]++;
				break;	
			}
		}
			
			foreach($a5 as $term)
		{
			if($a1[$i]==$term)
			{
				$counter[$i]++;
				break;	
			}
		}
		
	}
	return $counter;
}
*/

function inversedocumentfreq($df)
{
	for($i=0;$i<count($df);$i++)
	$idf[$i]=log10(6/$df[$i])/log10(2);
	return $idf;
}

function weight($tf,$idf)
{
	for($i=0;$i<count($tf);$i++)
	{
		$w[$i]=$tf[$i]*$idf[$i];
	}
	return $w;
}

function cosSim($Qarray,$array,$wq,$w)
{
	$num=0;
	$din1=0;
	$din2=0;
	for($i=0;$i<count($Qarray);$i++)
	{
		for($j=0;$j<count($array);$j++)
		{
			if($Qarray[$i]==$array[$j])
			{
				$num+=($wq[$i]*$w[$j]);
			}
		}
		
	}
	for($i=0;$i<count($Qarray);$i++)
	{
		$din1+=pow($wq[$i],2);
	}
	for($j=0;$j<count($array);$j++)
	{
		$din2+=pow($w[$j],2);
	}
	
	
	if($num==0)
	return 0;
	else
	return $num/sqrt($din1*$din2);
}
if(isset($_GET['q']))
{
	$q=$_GET['q'];
	$q=strtolower($q);
}

$diff1=Different_Terms($f1);
$array1=str_split($diff1);
$termFrequency1=termfreq($array1,$f1);

$diff2=Different_Terms($f2);
$array2=str_split($diff2);
$termFrequency2=termfreq($array2,$f2);

$diff3=Different_Terms($f3);
$array3=str_split($diff3);
$termFrequency3=termfreq($array3,$f3);

$diff4=Different_Terms($f4);
$array4=str_split($diff4);
$termFrequency4=termfreq($array4,$f4);

$diff5=Different_Terms($f5);
$array5=str_split($diff5);
$termFrequency5=termfreq($array5,$f5);

$Qdiff=trim(count_chars($q,3));
$Qdiff=strtolower($Qdiff);

$Qarray=str_split($Qdiff);
//calculating term frequency for query 
$count=0;
$max=0;
//calculating frequency
foreach($Qarray as $term)
{
	$freq[$count]=substr_count($q,$term);
	
	if($freq[$count]>$max)
	$max=$freq[$count];
	$count++;
}
//calculating term frequency
for($i=0;$i<$count;$i++)
$tf[$i]=$freq[$i]/$max;

//idf for terms in D1
for($i=0;$i<count($array1);$i++)
{
	$df1[$i]=docfreq($array1[$i],$array1,$array2,$array3,$array4,$array5,$Qarray);
}
$idf1=inversedocumentfreq($df1);
//idf for terms in D2
for($i=0;$i<count($array2);$i++)
{
	$df2[$i]=docfreq($array2[$i],$array1,$array2,$array3,$array4,$array5,$Qarray);
}
$idf2=inversedocumentfreq($df2);
//idf for terms in D3
for($i=0;$i<count($array3);$i++)
{
	$df3[$i]=docfreq($array3[$i],$array1,$array2,$array3,$array4,$array5,$Qarray);
}
$idf3=inversedocumentfreq($df3);
//idf for terms in D4
for($i=0;$i<count($array4);$i++)
{
	$df4[$i]=docfreq($array4[$i],$array1,$array2,$array3,$array4,$array5,$Qarray);
}
$idf4=inversedocumentfreq($df4);
//idf for terms in D5
for($i=0;$i<count($array5);$i++)
{
	$df5[$i]=docfreq($array5[$i],$array1,$array2,$array3,$array4,$array5,$Qarray);
}
$idf5=inversedocumentfreq($df5);
//df for terms in query
for($i=0;$i<count($Qarray);$i++)
{
	$dfq[$i]=docfreq($Qarray[$i],$array1,$array2,$array3,$array4,$array5,$Qarray);
}
$idfq=inversedocumentfreq($dfq);
//weights of terms
$w1=weight($termFrequency1,$idf1);
$w2=weight($termFrequency2,$idf2);
$w3=weight($termFrequency3,$idf3);
$w4=weight($termFrequency4,$idf4);
$w5=weight($termFrequency5,$idf5);
$wq=weight($tf,$idfq);
// comparing query to documents 
$cosSim1=cosSim($Qarray,$array1,$wq,$w1);
$cosSim2=cosSim($Qarray,$array2,$wq,$w2);
$cosSim3=cosSim($Qarray,$array3,$wq,$w3);
$cosSim4=cosSim($Qarray,$array4,$wq,$w4);
$cosSim5=cosSim($Qarray,$array5,$wq,$w5);
$sort_array=array($cosSim1,$cosSim2,$cosSim3,$cosSim4,$cosSim5);
for($i=0;$i<(count($sort_array)-1);$i++)
{
	for($j=($i+1);$j<count($sort_array);$j++)
	{
		if($sort_array[$i]<$sort_array[$j])
		{
			$temp=$sort_array[$i];
			$sort_array[$i]=$sort_array[$j];
			$sort_array[$j]=$temp;
		}
	}
}
$D1="/A.txt";
$D2="/B.txt";
$D3="/C.txt";
$D4="/D.txt";
$D5="/E.txt";

if($q!=null)
foreach($sort_array as $cosSim)
{
	if(($cosSim==$cosSim1)&&($flag1!=1))
	{
		echo "<div class='sh'><span class='display'>".basename($D1,".txt")."</span><span class='cos'>".$cosSim1."</span><br/>";
    echo "<a href='A.txt' download='Document1.txt'>Download A</a></span></div><br>";

		$flag1=1;
	}
	elseif(($cosSim==$cosSim2)&&($flag2!=2))
	{
		echo"<div class='sh'><span class='display'>". basename($D2,".txt")."</span><span class='cos'>".$cosSim2."</span><br>";
	echo "<a href='B.txt' download='Document2.txt'>Download B</a></span></div><br>";
		$flag2=2;
	}
	elseif(($cosSim==$cosSim3)&&($flag3!=3))
	{
	    echo "<div class='sh'><span class='display'>".basename($D3,".txt")."</span><span class='cos'>".$cosSim3."</span><br>";
		echo "<a href='C.txt' download='Document3.txt'>Download C</a></span></div><br>";
		$flag3=3;
	}
	elseif(($cosSim==$cosSim4)&&($flag4!=4))
	{
		echo "<div class='sh'><span class='display'>".basename($D4,".txt")."</span><span class='cos'>".$cosSim4."</span><br/>";
		echo "<a href='D.txt' download='Document4.txt'>Download D</a></span></div><br>";
		$flag4=4;
	}
	else
	{
		echo "<div class='sh'><span class='display'>".basename($D5,".txt")."</span><span class='cos'>".$cosSim5."</span><br/>";
		echo "<a href='E.txt' download='Document5.txt'>Download E</a></span></div><br>";
	}
}

$Doc=array($array1,$array2,$array3,$array4,$array5);
$adjMat=array_fill(0,5,0);
for($i=0;$i<count($adjMat);$i++)
{
	$adjMat[$i]=array_fill(0,5,0);
}
for($i=0;$i<count($adjMat);$i++)
{
	if(in_array("a",$Doc[$i]))
	$adjMat[$i][0]=1;
}
for($i=0;$i<count($adjMat);$i++)
{
	if(in_array("b",$Doc[$i]))
	$adjMat[$i][1]=1;
}
for($i=0;$i<count($adjMat);$i++)
{
	if(in_array("c",$Doc[$i]))
	$adjMat[$i][2]=1;
}
for($i=0;$i<count($adjMat);$i++)
{
	if(in_array("d",$Doc[$i]))
	$adjMat[$i][3]=1;
}
for($i=0;$i<count($adjMat);$i++)
{
	if(in_array("e",$Doc[$i]))
	$adjMat[$i][4]=1;
}
for($i=0;$i<count($adjMat);$i++)
{
	for($j=0;$j<count($adjMat[$i]);$j++)
	{
		if($i==$j)
		$adjMat[$i][$j]=0;
	}
}
$adjMat_trans=array();
for($i=0;$i<count($adjMat);$i++)
{
	for($j=0;$j<count($adjMat[$j]);$j++)
	{
		$adjMat_trans[$i][$j]=$adjMat[$j][$i];
	}
}
$hub0=array_fill(0,5,1);

for($i=0;$i<count($adjMat);$i++)
{
	$authority0[$i]=0;
	
	for($j=0;$j<count($adjMat[$j]);$j++)
	{
		$authority0[$i]+=$adjMat_trans[$i][$j]*$hub0[$j];
	}
}
$aVec=array();
$hubVec=array();
for($i=0;$i<count($adjMat);$i++)
{
	for($k=0;$k<count($adjMat_trans[$i]);$k++)
	{
		$aVec[$i][$k]=0;
		$hubVec[$i][$k]=0;
		for($j=0;$j<count($adjMat_trans);$j++)
		{
			$aVec[$i][$k]+=$adjMat_trans[$i][$j]*$adjMat[$j][$k];
			$hubVec[$i][$k]+=$adjMat[$i][$j]*$adjMat_trans[$j][$k];
		}
	}
}
$a=array();
$h=array();$sum=0;
for($i=0;$i<count($aVec);$i++)
{
	$a[$i]=0;
	for($j=0;$j<count($authority0);$j++)
	{
		$a[$i]+=$aVec[$i][$j]*$authority0[$j];
	}
	$sum+=pow($a[$i],2);
}
//normalization
for($i=0;$i<count($a);$i++){
$a[$i]=$a[$i]/sqrt($sum);}

$sum=0;
for($i=0;$i<count($hubVec);$i++)
{
	$h[$i]=0;
	for($j=0;$j<count($hub0);$j++)
	{
		$h[$i]+=$hubVec[$i][$j]*$hub0[$j];
	}
	$sum+=pow($h[$i],2);
}
//normalization
for($i=0;$i<count($h);$i++)
$h[$i]=$h[$i]/sqrt($sum);

//20 iterations
$af=array();
$hf=array();
for($m=0;$m<19;$m++)
{
	//calculating authority
	$af=$a;$sum1=0;
	for($i=0;$i<count($aVec);$i++)
{
	$a[$i]=0;
	for($j=0;$j<count($af);$j++)
	{
		$a[$i]+=$aVec[$i][$j]*$af[$j];
	}
	$sum1+=pow($a[$i],2);
}
for($i=0;$i<count($a);$i++)
$a[$i]=$a[$i]/sqrt($sum1);
//calculating hub
$hf=$h;$sum2=0;
for($i=0;$i<count($hubVec);$i++)
{
	$h[$i]=0;
	for($j=0;$j<count($hf);$j++)
	{
		$h[$i]+=$hubVec[$i][$j]*$hf[$j];
	}
	$sum2+=pow($h[$i],2);
}
for($i=0;$i<count($h);$i++)
$h[$i]=$h[$i]/sqrt($sum2);
}
$authority_scores=$a;
//sorting authorities
for($i=0;$i<(count($a)-1);$i++)
{
	for($j=($i+1);$j<count($a);$j++)
	{
		if($a[$i]<$a[$j])
		{
			$temp=$a[$i];
			$a[$i]=$a[$j];
			$a[$j]=$temp;
		}
	}
}


echo "<div class='authority'>"."<span class='display' >"."Document"."</span>"."<span class='score' >"."Authority Score"."</span>"."<span class='hub'>"."Hub Score"."</span>"."</div><br/>";
foreach($a as $authority)
{
	if(($authority==$authority_scores[0])&&($fl1!=1))
	{
		echo"<div class='sh'>"."<span class='display'>".basename($D1,".txt")."</span>"."<span class='cos'>".$authority."</span><span class='hub'>".$h[0]."</span>"."</div><br/>";
		$fl1=1;
	}
	elseif(($authority==$authority_scores[1])&&($fl2!=1))
	{
		echo"<div class='sh'>"."<span class='display'>".basename($D2,".txt")."</span>"."<span class='cos'>".$authority."</span><span class='hub'>".$h[1]."</span>"."</div><br/>";
		$fl2=1;
	}
	
	elseif(($authority==$authority_scores[2])&&($fl3!=1))
	{
		echo"<div class='sh'>"."<span class='display'>".basename($D3,".txt")."</span>"."<span class='cos'>".$authority."</span><span class='hub'>".$h[2]."</span>"."</div><br/>";
		$fl3=1;
	}
	
	elseif(($authority==$authority_scores[3])&&($fl4!=1))
	{
		echo"<div class='sh'>"."<span class='display'>".basename($D4,".txt")."</span>"."<span class='cos'>".$authority."</span><span class='hub'>".$h[3]."</span>"."</div><br/>";
		$fl4=1;
	}
	else
	{
	
	echo"<div class='sh'>"."<span class='display'>".basename($D5,".txt")."</span>"."<span class='cos'>".$authority."</span><span class='hub'>".$h[4]."</span>"."</div><br/>";
		$fl5=1;
	
	}
}
/*
for($i=0;$i<count($aVec);$i++)
{
	for($j=0;$j<count($aVec[$i]);$j++)
	echo $hubVec[$i][$j];
	echo"<br/>";	
}
*/


?>
<!--<script src="js/mine.js"></script>-->

</body>
</html>
