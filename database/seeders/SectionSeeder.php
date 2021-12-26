<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;
use App\Models\Section;
use App\Models\Question;
use App\Models\Learning;
use App\Models\User;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(self::sectionsData as $sectionData){
            $section = Section::factory()->create([
                "title"=>$sectionData["title"],
                "description"=>$sectionData["description"],
                "series_id"=>$sectionData["series_id"],
            ]);
            foreach($sectionData["question"] as $qdata){
                Question::factory()->create([
                    "front"=>$qdata["front"],
                    "back"=>$qdata["back"],
                    "section_id"=>$section->id
                ]);
            }
        }
    }
    const sectionsData = [
        ["title"=>"可算名詞・不可算名詞",
        "description"=>"数えられる名詞、数えられない名詞を学びます",
        "series_id"=>1,
        "question"=>self::countableData],
        ["title"=>"数",
        "description"=>"混乱しがちな英語での数の数え方を学びます",
        "series_id"=>2,
        "question"=>self::numberData],
        ["title"=>"現在・未来の条件節",
        "description"=>"If + 現在形で、現在や未来の出来事に関する条件節を作れます",
        "series_id"=>3,
        "question"=>self::conditionData],
        ["title"=>"仮定法過去",
        "description"=>"If + 過去形とwould+原型で、現状と異なる反実仮想について語れます",
        "series_id"=>4,
        "question"=>self::subjunctiveData],
        ["title"=>"仮定法過去完了",
        "description"=>"If + 過去完了とwould have+完了形で、過去と異なる反実仮想について語れます",
        "series_id"=>4,
        "question"=>self::subjunctivePastData]
    ];
    const countableData = [
        [
            "front"=>"私はアドバイスを受けた／職探しにおける","back"=>"I got advice(不可算) on finding a job"
        ],
        [
            "front"=>"彼は私に与えた／3つのアドバイスを","back"=>"He gave me three pieces of advice"
        ],
        [
            "front"=>"彼は大変な進歩をした","back"=>"He made a lot of progress(不可算)"
        ],
        [
            "front"=>"中国経済は大変な成長を見せた","back"=>"Chinese economy showed significant growth(不可算)"
        ],
        [
            "front"=>"そのニュースは口コミで広まった","back"=>"The news(不可算) was carried by word of mouth"
        ],
        [
            "front"=>"その土地は耕作された形跡があった","back"=>"The land showed proof of cultivation"   
        ],
        [
             "front"=>"あなたがその飛行機に持ち込めるのは一つの手荷物だけです","back"=>"You're only allowed one item of hand baggage on the plain"   
        ],
        [
            "front"=>"いくつの手荷物なら無料で持ち込めますか?","back"=>"How many pieces of baggage can I bring free of charge?"   
        ],
        [
            "front"=>"そのジムにはたくさんの設備があった","back"=>"The gym has a lot of equipment"   
       ]
    ];
    const numberData = [
        [
            "front"=>"123","back"=>"a handred and twenty-three"
        ],
        [
            "front"=>"3,456","back"=>"three thousand four handred and fifty-six"
        ],
        [
            "front"=>"56,123","back"=>"fifty-six thousand a handred and twenty-three"
        ],
        [
            "front"=>"234,567","back"=>"two handred and thirty-four thousand and five handred and sixty-seven"
        ],
        [
            "front"=>"1,654,789","back"=>"one-million six handred and fifty-four thousand seven handred and eighty-nine"
        ],
    ];
    const conditionData = [
        [
            "front"=>"君が正しければ、僕は間違っている","back"=>"If you're right, I'm wrong"
        ],
        [
            "front"=>"私は君に会う／もし私が東京を訪れれば","back"=>"I'll see you if I visit Tokyo"
        ],
        [
            "front"=>"もし明日が曇りなら／私はサイクリングに出かける","back"=>"If it's cloudy tomorrow, I'll go cycling"
        ],
        [
            "front"=>"私はトムにプレゼントを渡す／彼が出ていく前に","back"=>"I'll give Tom a present before he leaves"
        ],
        [
            "front"=>"私は家に帰る／彼らが仕事を終えたときに","back"=>"I'll go home when they finish work"
        ],
        [
            "front"=>"私は立ち去れない／その客人が去るまで","back"=>"I can't leave until the guest leaves"
        ],
        [
            "front"=>"君が同意しないかぎり、私はそれをしない","back"=>"Unless you consent, I won't do it"
        ],
        [
            "front"=>"安静にしている限り、それは治るのに長くかからない","back"=>"As long as you take it easy, it doesn't take long to heal"
        ],
        [
            "front"=>"私は家にいる／雨が降った場合には","back"=>"I'll stay home in case it rains"
        ],
        [
            "front"=>"それを買おう／低価格だったならば","back"=>"I'll buy it providing that it's in low price"
        ],
    ];
    const subjunctiveData = [
        [
            "front"=>"もし彼の住所を知っていたら、私は彼に手紙を書いただろう","back"=>"If I knew his address, I would write to him"
        ],
        [
            "front"=>"もう一度若返れたらなあ","back"=>"I wish I were young again"
        ],
        [
            "front"=>"彼は歌います／まるでプロであるかのように","back"=>"He sings as if he were a professional"
        ],
        [
            "front"=>"これについて何か手を打つ時だ","back"=>"It's abount time something were done about this"
        ],
        [
            "front"=>"もし私が金持ちなら(倒置)、これを変えたのに","back"=>"Were I rich, I could buy this"
        ],
        [
            "front"=>"もしあなたの助けがなければ(for)、私は事業に失敗するでしょう","back"=>"If it weren't for your help, I would fail in my business"
        ],
        [
            "front"=>"もしあなたの助けがなければ(without)、私は事業に失敗するでしょう","back"=>"Without your help, I would fail in my business"
        ],
        [
            "front"=>"彼が英語をしゃべるところを聞いたら、君は彼をネイティブスピーカーだと思うでしょう","back"=>"To hear him speak English, you would take him for a native speaker"
        ],
        [
            "front"=>"同じことが病院で起こったら大災害になるでしょう","back"=>"The same thing, happening in a hospital, would amount to disaster"
        ],
    ];
    const subjunctivePastData = [
        [
            "front"=>"もし彼の住所を知っていたら、私は彼に手紙を書いただろう","back"=>"If I had knew his address, I would have writen to him"
        ],
        [
            "front"=>"あの時彼のアドバイスに従っていたらなあ","back"=>"I wish I had followed his advice then"
        ],
        [
            "front"=>"何か忘れ物でもしたような気分だ","back"=>"I feel as if I had left something behind"
        ],
        [
            "front"=>"もしあなたの到着を知っていたら(到着)、あなたに逢ったのに","back"=>"Had I known your arrival, I would have met you"
        ],
        [
            "front"=>"一人にされたら彼女は道に迷っただろう","back"=>"Left to herself, she would have lost her way"
        ],
    ];
    const subjunctiveFutureData = [
        [
            "front"=>"万が一彼が来たら、私はそれを彼に告げようと思う","back"=>"If he should come, I will tell it to him"
        ],
    ];
}