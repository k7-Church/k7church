<?php

/**
 * TpPostsUsuarios
 *
 * Essa é a classe geral do nosso plugin
 */
if ( ! class_exists('TpPostsUsuarios') ) {

    class TpPostsUsuarios
    {

        /*
         * Caso ocorra um erro, utilizaremos esta propriedade para exibi-lo
         */
        public static $erro;

        /**
         * Construtor da classe
         *
         * Carrega todos os métodos que precisamos ao instanciar a classe.
         */
        public function __construct()
        {
            add_shortcode('get_Testimonies', array($this, 'form_of_the_user'));

            // Registra um custom post exclusivo para os usuários
            add_action('init', array($this, 'registra_posts_usuarios'));
            add_action('parse_request', array($this, 'send_data'));
        }
        /**
         * Registra um custom post type exclusivo para os usuários
         */
        public function registra_posts_usuarios() {
            $labels = array(
                'name'               => 'Testimony ',
                'singular_name'      => 'Testimony ',
                'menu_name'          => 'Testimonies ',
                'name_admin_bar'     => 'Testimonies',
                'add_new'            => 'New Testimony',
                'add_new_item'       => 'New Testimony',
                'new_item'           => 'New Testimony',
                'edit_item'          => 'Editar',
                'view_item'          => 'Visualizar',
                'all_items'          => 'Testimonies',
                'search_items'       => 'Encontrar',
                'parent_item_colon'  => 'Pais:',
                'not_found'          => 'Nada encontrado.',
                'not_found_in_trash' => 'Nada encontrado.',
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'rewrite'            => array('slug' => '_tp_posts_externos'),
                'can_export'         => true,
                /*'taxonomies'         => array('post_tag'),*/
                'supports'           => array(
                    'title',
                    'editor',
                    'author',
                    /*'thumbnail',*/
                    /*'excerpt',*/
                    /*'trackbacks',*/
                    /*'custom-fields',*/
                    /*'comments',*/
                    /*'revisions',*/
                    /*'page-attributes',*/
                    /*'post-formats'*/
                ),
            );

            // Registra o custom post
            register_post_type( '_tp_posts_externos', $args );
        }

        /**
         * Este método irá receber os dados enviados pelo formulário
         */
        public function send_data () {

            /* Verifica se os dados do formulário foram enviados */
            if (
                'POST' != $_SERVER['REQUEST_METHOD']
                || ! isset( $_POST['_tp_titulo'] )
                || ! isset( $_POST['_tp_conteudo'] )
            ) {
                return;
            }

            // Configura o post para uma variável
            $dados = $_POST;

            // Título
            $titulo = !empty($dados['_tp_titulo']) ? $dados['_tp_titulo'] : null;
            $titulo = sanitize_text_field($titulo);

            // Conteúdo
            $conteudo = !empty($dados['_tp_conteudo']) ? $dados['_tp_conteudo'] : null;

            // Verifica se os campos tem algum valor
            if ( ! $titulo || ! $conteudo ) {
                self::$erro = 'Você deve preencher todos os campos!';
                return;
            }

            // Verifica o campo nonce
            if (
                ! isset( $_POST['tutsup_posts_nonce'] )
                || ! wp_verify_nonce( $_POST['tutsup_posts_nonce'], 'tutsup_posts_usuarios' )
            ) {
                self::$erro = 'Erro ao enviar formulário!';
                return;
            }

            // Cria os dados do post
            $dados_post = array(
                'post_title'    => $titulo, // Título do post
                'post_content'  => $conteudo, // Conteúdo do post
                'post_status'   => 'pending', // Status do post
                'post_type'     => '_tp_posts_externos', // Tipo do post
                'post_author'   => 2 // Autor do post
            );

            // Tenta inserir o post
            $post_id = wp_insert_post( $dados_post );

            // Se o post for inserido com sucesso, teremos o ID do mesmo
            if ( ! $post_id ) {
                self::$erro = 'Erro ao enviar post!';
                return;
            }
        }

        /**
         * Este é um formulário HTML muito básico
         */
        public function form_of_the_user() {

            // Variável temporária para nosso erro
            $erro = null;

            // Verifica se existe algum erro e exibe
            if ( self::$erro ) {
                $erro = '<p>' . self::$erro . '</p>';
            }
            // Se não houver erros e o formulário foi enviado, o post foi
            // Inserido com sucesso
            elseif (
                ! self::$erro
                && isset( $_POST['_tp_titulo'] )
                && isset( $_POST['_tp_conteudo'] )
            ) {
                $erro = '<p> !</p>';
            }
            ?>

            <form action="" method="post">

                <p>
                    Título:<br>
                    <input type="text" value="<?php
                    echo esc_attr(stripslashes(@$_POST['_tp_titulo']));
                    ?>" name="_tp_titulo">
                </p>

                <p>
                    Conteúdo:<br>
                    <textarea name="_tp_conteudo"><?php
                        echo esc_attr(stripslashes(@$_POST['_tp_conteudo']));
                        ?></textarea>
                </p>

                <p>
                    <?php
                    // Mostra o erro (se não houver um erro, mostra nada)
                    echo $erro;

                    // Adiciona nosso campo nonce
                    wp_nonce_field('tutsup_posts_usuarios', 'tutsup_posts_nonce');
                    ?>
                    <input type="submit" value="Enviar">
                </p>

            </form>

            <?php
        }

    } // TpPostsUsuarios

    /* Loads the class */
    $TutsupContato = new TpPostsUsuarios();

} // class_exists('TpPostsUsuarios')
?>